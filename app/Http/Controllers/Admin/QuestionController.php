<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\QuestionEngine\QuestionParser;
use App\Services\QuestionEngine\QuestionRenderer;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with([
            'primarySubcategory.category',
            'secondarySubcategory.category'
        ])->latest()->get();

        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $categories = Category::with('subcategories')->get();
        return view('admin.questions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'primary_subcategory_id' => 'required|exists:subcategories,id',
            'secondary_subcategory_id' => 'nullable|different:primary_subcategory_id|exists:subcategories,id',
            'question_type' => 'required|string|in:multiselect,singleselect,dropdown,drag_and_drop,truefalse',
            'xml_content' => 'required|string',
        ]);

        $validated['xml_content'] = $this->processXmlContent($validated['xml_content']);

        $question = Question::create($validated);

        return redirect()->route('admin.questions.set-answer', $question)
            ->with('success', 'Question metadata saved! Now, please select the correct answer below.');
    }

    public function edit(Question $question)
    {
        $categories = Category::with('subcategories')->get();
        return view('admin.questions.edit', compact('question', 'categories'));
    }

    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'primary_subcategory_id' => 'required|exists:subcategories,id',
            'secondary_subcategory_id' => 'nullable|different:primary_subcategory_id|exists:subcategories,id',
            'question_type' => 'required|string|in:multiselect,singleselect,dropdown,drag_and_drop,truefalse',
            'xml_content' => 'required|string',
        ]);

        $validated['xml_content'] = $this->processXmlContent($validated['xml_content']);

        $question->update($validated);

        return redirect()->route('admin.questions.set-answer', $question)
            ->with('success', 'Question metadata updated! Now, please select/verify the correct answer below.');
    }

    public function setAnswer(Question $question)
    {
        $parser = new QuestionParser();
        $renderer = new QuestionRenderer();

        $parsed = $parser->parse($question->xml_content);
        $parsed = $renderer->render($parsed);

        return view('admin.questions.set-answer', compact('parsed', 'question'));
    }

    public function storeAnswer(Request $request, Question $question)
    {
        $validated = $request->validate([
            'correct_answer_string' => 'nullable|string|max:1000',
        ]);

        $driver = \App\Services\QuestionEngine\Drivers\QuestionDriverFactory::make($question->question_type);
        $formattedAnswer = $driver->formatAnswer($validated['correct_answer_string'] ?? '');

        $question->update([
            'correct_answer_string' => $formattedAnswer,
        ]);

        return redirect()->route('admin.questions.preview', $question)
            ->with('success', 'Question correct answer configured successfully!');
    }

    public function destroy(Question $question)
    {
        // Optional: delete associated images from disk if we parsed them.
        // We'll keep it simple and delete the database record first.
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question deleted successfully!');
    }

    public function clone(Question $question)
    {
        $cloned = Question::create([
            'primary_subcategory_id' => $question->primary_subcategory_id,
            'secondary_subcategory_id' => $question->secondary_subcategory_id,
            'question_type' => $question->question_type,
            'xml_content' => $question->xml_content,
            'correct_answer_string' => $question->correct_answer_string,
        ]);

        return redirect()->route('admin.questions.edit', $cloned)
            ->with('success', 'Question cloned successfully! You can now modify it slightly.');
    }

    public function preview(Question $question)
    {
        $parser = new QuestionParser();
        $renderer = new QuestionRenderer();

        $parsed = $parser->parse($question->xml_content);
        $parsed = $renderer->render($parsed);

        // Find next and previous question IDs for Slideshow mode
        $prevQuestion = Question::where('id', '<', $question->id)->orderBy('id', 'desc')->first();
        $nextQuestion = Question::where('id', '>', $question->id)->orderBy('id', 'asc')->first();

        $prevId = $prevQuestion ? $prevQuestion->id : null;
        $nextId = $nextQuestion ? $nextQuestion->id : null;

        $categories = Category::with('subcategories')->get();

        return view('questions.preview', compact('parsed', 'question', 'prevId', 'nextId', 'categories'));
    }

    public function updateMapping(Request $request, Question $question)
    {
        $validated = $request->validate([
            'primary_subcategory_id' => 'required|exists:subcategories,id',
            'secondary_subcategory_id' => 'nullable|different:primary_subcategory_id|exists:subcategories,id',
        ]);

        $question->update($validated);

        return back()->with('success', 'Question category mapping updated successfully!');
    }

    public function show(Question $question)
    {
        return $this->preview($question);
    }


    /**
     * Extracts embedded base64 images, decodes and saves them to storage,
     * and replaces the <img src="data:..."> with an @img('filename') token
     * so the renderer can generate a signed URL at preview time.
     */
    protected function processXmlContent(string $xmlContent): string
    {
        // Match full <img ...> tags that contain a base64 src attribute
        $pattern = '/<img[^>]*src=["\']data:image\/([^;]+);base64,([^"\']+)["\'][^>]*>/i';

        return preg_replace_callback($pattern, function ($matches) {
            $extension = $matches[1]; // e.g. png, jpeg, gif
            $base64Data = $matches[2];

            $imageBinary = base64_decode($base64Data);
            if ($imageBinary === false) {
                return $matches[0]; // fallback if decoding fails
            }

            $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
            $uuid = Str::uuid();
            $storagePath = 'questions/' . $uuid . '.' . $extension;
            Storage::disk($disk)->put($storagePath, $imageBinary);

            // Store only the token — signed URL is generated at render time
            return "@img('{$uuid}.{$extension}')";
        }, $xmlContent);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:20480',
        ]);

        if ($request->hasFile('image')) {
            $disk     = config('filesystems.default') === 's3' ? 's3' : 'public';
            $file     = $request->file('image');
            $filename = Str::uuid() . '.jpg';
            $storagePath = 'questions/' . $filename;

            // Resize with GD if extension is loaded, otherwise upload original
            if (extension_loaded('gd')) {
                $imageData = $this->resizeImage($file->getRealPath(), 1400, 85);
            } else {
                $imageData = file_get_contents($file->getRealPath());
            }

            Storage::disk($disk)->put($storagePath, $imageData);

            // Generate a secure relative proxy URL for the editor thumbnail preview
            $previewUrl = route('image.proxy', ['filename' => $filename], false);

            return response()->json([
                'success'     => true,
                'preview_url' => $previewUrl,
                'filename'    => $filename,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    /**
     * Resize an image to fit within $maxDimension x $maxDimension (maintains aspect ratio)
     * and re-encode as JPEG at the given quality. Uses PHP GD (always available).
     */
    protected function resizeImage(string $sourcePath, int $maxDimension, int $quality): string
    {
        [$origW, $origH, $type] = getimagesize($sourcePath);

        // Create source image from the original
        $src = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG  => imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF  => imagecreatefromgif($sourcePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($sourcePath),
            IMAGETYPE_BMP  => imagecreatefrombmp($sourcePath),
            default        => imagecreatefromjpeg($sourcePath),
        };

        // Calculate new dimensions keeping aspect ratio
        if ($origW > $maxDimension || $origH > $maxDimension) {
            $ratio  = min($maxDimension / $origW, $maxDimension / $origH);
            $newW   = (int) round($origW * $ratio);
            $newH   = (int) round($origH * $ratio);
        } else {
            $newW = $origW;
            $newH = $origH;
        }

        $dst = imagecreatetruecolor($newW, $newH);

        // Preserve transparency for PNG/GIF
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $transparent);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        // Capture output as JPEG
        ob_start();
        imagejpeg($dst, null, $quality);
        $jpeg = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $jpeg;
    }

    public function siblings()
    {
        // Load all active questions grouped by Category
        $categories = Category::with(['subcategories.primaryQuestions' => function($query) {
            $query->with('primarySubcategory')->select('id', 'primary_subcategory_id', 'question_type', 'xml_content', 'sibling_group_id');
        }])->get();

        $proposals = [];

        // Load ignored sibling pairs
        $ignored = \DB::table('ignored_sibling_pairs')->get();
        $ignoredMap = [];
        foreach ($ignored as $row) {
            $ignoredMap[$row->question_id_1][$row->question_id_2] = true;
            $ignoredMap[$row->question_id_2][$row->question_id_1] = true;
        }

        foreach ($categories as $category) {
            // Gather all questions belonging to this Category
            $questions = collect();
            foreach ($category->subcategories as $sub) {
                $questions = $questions->concat($sub->primaryQuestions);
            }

            $count = $questions->count();
            if ($count < 2) {
                continue;
            }

            // Pre-calculate stripped texts to save parsing overhead
            $strippedTexts = [];
            foreach ($questions as $q) {
                $strippedTexts[$q->id] = $this->getStrippedText($q->xml_content);
            }

            // Compare all combinations in this Category
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $q1 = $questions[$i];
                    $q2 = $questions[$j];

                    // Determine if already linked in the same sibling group
                    $isLinked = ($q1->sibling_group_id !== null && $q1->sibling_group_id === $q2->sibling_group_id);

                    // Skip if they are in ignored_sibling_pairs (only check if they are not already linked)
                    if (!$isLinked && (isset($ignoredMap[$q1->id][$q2->id]) || isset($ignoredMap[$q2->id][$q1->id]))) {
                        continue;
                    }

                    $text1 = $strippedTexts[$q1->id];
                    $text2 = $strippedTexts[$q2->id];

                    if (empty($text1) || empty($text2)) {
                        continue;
                    }

                    similar_text($text1, $text2, $percent);

                    if ($isLinked || $percent >= 75) {
                        $proposals[] = [
                            'q1' => $q1,
                            'q2' => $q2,
                            'similarity' => round($percent),
                            'text1' => mb_strimwidth($text1, 0, 150, '...'),
                            'text2' => mb_strimwidth($text2, 0, 150, '...'),
                            'category_name' => $category->name,
                            'q1_sub_name' => $q1->primarySubcategory->name ?? 'None',
                            'q2_sub_name' => $q2->primarySubcategory->name ?? 'None',
                            'is_linked' => $isLinked,
                        ];
                    }
                }
            }
        }

        // Sort proposals: unlinked matches first, then linked matches. Inside each sort by similarity desc.
        usort($proposals, function($a, $b) {
            if ($a['is_linked'] !== $b['is_linked']) {
                return $a['is_linked'] ? 1 : -1;
            }
            return $b['similarity'] <=> $a['similarity'];
        });

        return view('admin.questions.siblings', compact('proposals'));
    }

    public function acceptSibling(Request $request)
    {
        $validated = $request->validate([
            'q1_id' => 'required|exists:questions,id',
            'q2_id' => 'required|exists:questions,id',
        ]);

        $q1 = Question::findOrFail($validated['q1_id']);
        $q2 = Question::findOrFail($validated['q2_id']);

        if ($q1->sibling_group_id !== null && $q2->sibling_group_id !== null) {
            // Merge both groups to group 1
            $oldGroup2 = $q2->sibling_group_id;
            Question::where('sibling_group_id', $oldGroup2)->update([
                'sibling_group_id' => $q1->sibling_group_id
            ]);
        } elseif ($q1->sibling_group_id !== null) {
            $q2->update(['sibling_group_id' => $q1->sibling_group_id]);
        } elseif ($q2->sibling_group_id !== null) {
            $q1->update(['sibling_group_id' => $q2->sibling_group_id]);
        } else {
            $newGroupId = (Question::max('sibling_group_id') ?? 0) + 1;
            $q1->update(['sibling_group_id' => $newGroupId]);
            $q2->update(['sibling_group_id' => $newGroupId]);
        }

        return back()->with('success', 'Questions linked as siblings successfully!');
    }

    public function rejectSibling(Request $request)
    {
        $validated = $request->validate([
            'q1_id' => 'required|exists:questions,id',
            'q2_id' => 'required|exists:questions,id',
        ]);

        $id1 = min($validated['q1_id'], $validated['q2_id']);
        $id2 = max($validated['q1_id'], $validated['q2_id']);

        \DB::table('ignored_sibling_pairs')->updateOrInsert([
            'question_id_1' => $id1,
            'question_id_2' => $id2,
        ], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Sibling recommendation dismissed.');
    }

    public function unpairSibling(Request $request)
    {
        $validated = $request->validate([
            'q1_id' => 'required|exists:questions,id',
            'q2_id' => 'required|exists:questions,id',
        ]);

        $q1 = Question::findOrFail($validated['q1_id']);
        $q2 = Question::findOrFail($validated['q2_id']);

        $q1->update(['sibling_group_id' => null]);
        $q2->update(['sibling_group_id' => null]);

        return back()->with('success', 'Questions unpaired successfully!');
    }

    private function getStrippedText($xmlContent)
    {
        $parser = new QuestionParser();
        try {
            $parsed = $parser->parse($xmlContent);
            return trim(strip_tags($parsed['text'] ?? ''));
        } catch (\Exception $e) {
            return trim(strip_tags($xmlContent));
        }
    }
}
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

        return view('questions.preview', compact('parsed', 'question', 'prevId', 'nextId'));
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
}
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
            'correct_answer_string' => 'nullable|string|max:255',
        ]);

        $validated['xml_content'] = $this->processXmlContent($validated['xml_content']);

        Question::create($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question added successfully! Any embedded base64 images were extracted.');
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
            'correct_answer_string' => 'nullable|string|max:255',
        ]);

        $validated['xml_content'] = $this->processXmlContent($validated['xml_content']);

        $question->update($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question updated successfully!');
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

        return view('questions.preview', compact('parsed', 'question'));
    }

    /**
     * Extracts embedded base64 images, decodes and saves them to local storage,
     * and returns the updated XML with public storage URLs.
     */
    protected function processXmlContent(string $xmlContent): string
    {
        // This looks for: src="data:image/png;base64,iVBORw0KGgo..."
        $pattern = '/src=["\']data:image\/([^;]+);base64,([^"\']+)["\']/i';

        return preg_replace_callback($pattern, function ($matches) {
            $extension = $matches[1]; // e.g. png, jpeg, gif
            $base64Data = $matches[2]; // Base64 raw block

            $imageBinary = base64_decode($base64Data);
            if ($imageBinary === false) {
                return $matches[0]; // fallback if decoding fails
            }

            $filename = 'questions/' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($filename, $imageBinary);

            $publicUrl = Storage::url($filename);
            return 'src="' . $publicUrl . '"';
        }, $xmlContent);
    }
}
<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\QuestionEngine\QuestionParser;
use App\Services\QuestionEngine\QuestionRenderer;

class QuestionController extends Controller
{
    public function index()
    {
        $questions = Question::latest()->get();
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        return view('admin.questions.create');
    }

    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'subcategory' => 'nullable|string|max:255',
            'question_type' => 'required|string|max:255',
            'xml_content' => 'required|string',
            'correct_answer_string' => 'nullable|string|max:255',
        ]);

        $xmlContent = $validated['xml_content'];

        // 2. The Regex Pattern to find Base64 images
        // This looks for: src="data:image/png;base64,iVBORw0KGgo..."
        $pattern = '/src=["\']data:image\/([^;]+);base64,([^"\']+)["\']/i';

        // 3. Scan and Replace
        $processedXml = preg_replace_callback($pattern, function ($matches) {
            $extension = $matches[1]; // e.g., png, jpeg, gif
            $base64Data = $matches[2]; // The massive block of text

            // Decode the massive text back into a physical image file
            $imageBinary = base64_decode($base64Data);

            // Generate a unique filename
            $filename = 'questions/' . Str::uuid() . '.' . $extension;

            // Save it to the persistent public disk we mounted in Coolify
            Storage::disk('public')->put($filename, $imageBinary);

            // Return the new clean path to inject back into the XML string
            $publicUrl = Storage::url($filename);
            
            return 'src="' . $publicUrl . '"';
            
        }, $xmlContent);

        // 4. Overwrite the massive XML with the clean, processed XML
        $validated['xml_content'] = $processedXml;

        // 5. Save everything to the database
        Question::create($validated);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Question added successfully! Any embedded images were extracted and saved safely.');
    }

    public function preview(Question $question)
{
    $parser = new QuestionParser();
    $renderer = new QuestionRenderer();

    $parsed = $parser->parse($question->xml_content);
    $parsed = $renderer->render($parsed);

    return view('questions.preview', compact('parsed', 'question'));
}
}
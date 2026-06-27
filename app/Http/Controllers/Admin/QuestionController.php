<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Show the list of questions
    public function index()
    {
        $questions = Question::latest()->get();
        return view('admin.questions.index', compact('questions'));
    }

    // Show the form to add a new question
    public function create()
    {
        return view('admin.questions.create');
    }

    // Save the new question
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subcategory' => 'nullable|string|max:255',
            'question_type' => 'required|string|max:255',
            'xml_content' => 'required|string',
            'correct_answer_string' => 'nullable|string|max:255',
        ]);

        // (Later, this is where you will add the logic to extract images from the XML)

        Question::create($validated);

        return redirect()->route('admin.questions.index')->with('success', 'Question added successfully!');
    }
}
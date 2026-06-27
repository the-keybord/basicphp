<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\Category;
use App\Models\Question;
use Illuminate\Http\Request;
use App\Services\QuestionEngine\QuestionParser;
use App\Services\QuestionEngine\QuestionRenderer;

class TestController extends Controller
{
    public function index()
    {
        $tests = Test::latest()->get();
        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        // Load categories with subcategories and count of questions in each
        $categories = Category::with(['subcategories' => function ($q) {
            $q->withCount('primaryQuestions');
        }])->get();

        return view('admin.tests.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'questions' => 'required|array', // subcategory_id => count
        ]);

        $questionIds = [];

        foreach ($request->questions as $subId => $count) {
            $count = intval($count);
            if ($count > 0) {
                // Fetch random questions for this subcategory
                $ids = Question::where('primary_subcategory_id', $subId)
                    ->inRandomOrder()
                    ->take($count)
                    ->pluck('id')
                    ->toArray();

                $questionIds = array_merge($questionIds, $ids);
            }
        }

        if (empty($questionIds)) {
            return back()->withErrors(['questions' => 'You must select at least one question to generate a test.'])->withInput();
        }

        Test::create([
            'name' => $request->name,
            'question_ids' => $questionIds,
        ]);

        return redirect()->route('admin.tests.index')
            ->with('success', 'Test generated successfully with ' . count($questionIds) . ' random questions!');
    }

    public function preview(Test $test)
    {
        // Retrieve and sort questions in the exact order of the array
        $questionsMap = Question::with(['primarySubcategory.category', 'secondarySubcategory.category'])
            ->whereIn('id', $test->question_ids)
            ->get()
            ->keyBy('id');

        $orderedQuestions = collect($test->question_ids)
            ->map(fn($id) => $questionsMap->get($id))
            ->filter();

        $parser = new QuestionParser();
        $renderer = new QuestionRenderer();

        $renderedQuestions = $orderedQuestions->map(function ($q) use ($parser, $renderer) {
            $parsed = $parser->parse($q->xml_content);
            $parsed = $renderer->render($parsed);
            return [
                'model' => $q,
                'parsed' => $parsed,
            ];
        });

        return view('admin.tests.preview', compact('test', 'renderedQuestions'));
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')
            ->with('success', 'Test deleted successfully!');
    }
}

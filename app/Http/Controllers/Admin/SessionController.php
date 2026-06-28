<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\Question;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = TestSession::with(['accessCode.test'])->latest()->get();
        return view('admin.sessions.index', compact('sessions'));
    }

    public function interrupt(TestSession $session)
    {
        if ($session->completed_at) {
            return back()->with('error', 'This student session has already been completed.');
        }

        // Grade the session with whatever answers they currently have auto-saved
        $score = 0;
        $questions = Question::whereIn('id', $session->questions_order)->get()->keyBy('id');
        $answers = $session->answers ?? [];

        foreach ($session->questions_order as $qId) {
            $question = $questions->get($qId);
            if (!$question) continue;

            $userVal = $answers[$qId] ?? '';
            $cleanedUser = strtolower(trim((string)$userVal));
            $cleanedCorrect = strtolower(trim((string)$question->correct_answer_string));

            if ($cleanedUser === $cleanedCorrect && $cleanedCorrect !== '') {
                $score++;
            }
        }

        $session->update([
            'score' => $score,
            'is_interrupted' => true,
            'completed_at' => now(),
        ]);

        return back()->with('success', "Session for {$session->firstname} {$session->lastname} was successfully interrupted and submitted remotely!");
    }

    public function review(TestSession $session)
    {
        $session->load(['accessCode.test']);

        // Fetch questions in display order
        $questionsMap = Question::whereIn('id', $session->questions_order)->get()->keyBy('id');
        $orderedQuestions = collect($session->questions_order)
            ->map(fn($id) => $questionsMap->get($id))
            ->filter();

        $parser = new \App\Services\QuestionEngine\QuestionParser();
        $renderer = new \App\Services\QuestionEngine\QuestionRenderer();

        $renderedQuestions = $orderedQuestions->map(function ($q) use ($parser, $renderer) {
            $parsed = $parser->parse($q->xml_content);
            $parsed = $renderer->render($parsed);
            return [
                'model' => $q,
                'parsed' => $parsed,
            ];
        });

        return view('admin.sessions.review', compact('session', 'renderedQuestions'));
    }
}

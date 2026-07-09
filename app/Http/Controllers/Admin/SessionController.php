<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestSession;
use App\Models\Question;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $query = TestSession::with(['accessCode.test']);

        if ($request->filled('code')) {
            $code = strtoupper(trim($request->code));
            $query->whereHas('accessCode', function($q) use ($code) {
                $q->where('code', 'like', "%{$code}%");
            });
        }

        $sessions = $query->latest()->get();
        return view('admin.sessions.index', compact('sessions'));
    }

    public function interrupt(TestSession $session)
    {
        if ($session->completed_at) {
            return back()->with('error', 'This student session has already been completed.');
        }

        // Grade the session with whatever answers they currently have auto-saved
        $session->recalculateScore();

        $session->update([
            'is_interrupted' => true,
            'completed_at' => now(),
        ]);

        return back()->with('success', "Session for {$session->firstname} {$session->lastname} was successfully interrupted and submitted remotely!");
    }

    public function review(TestSession $session)
    {
        $session->load(['accessCode.test']);

        // Dynamically recalculate/update score when reviewing the session
        $session->recalculateScore();

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

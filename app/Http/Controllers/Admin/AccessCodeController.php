<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccessCode;
use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccessCodeController extends Controller
{
    public function index()
    {
        $codes = AccessCode::with('test')->latest()->get();
        return view('admin.codes.index', compact('codes'));
    }

    public function create()
    {
        $tests = Test::latest()->get();
        return view('admin.codes.create', compact('tests'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:testing,resource',
            'test_id' => 'required_if:type,testing|nullable|exists:tests,id',
            'resource_url' => 'required_if:type,resource|nullable|url',
            'expires_value' => 'nullable|integer|min:1',
            'expires_unit' => 'required_with:expires_value|string|in:minutes,hours,days',
        ]);

        // Generate unique 6-character uppercase code (excluding confusing characters O and 0)
        $pool = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
        do {
            $code = '';
            for ($i = 0; $i < 6; $i++) {
                $code .= $pool[random_int(0, strlen($pool) - 1)];
            }
        } while (AccessCode::where('code', $code)->exists());

        $rules = [];
        if ($request->type === 'testing') {
            $rules = [
                'mix_questions' => $request->has('rules.mix_questions'),
                'mix_options' => $request->has('rules.mix_options'),
                'hide_after_submit' => $request->has('rules.hide_after_submit'),
                'view_answers_after_submit' => $request->has('rules.view_answers_after_submit'),
                'view_correct_answers' => $request->has('rules.view_correct_answers'),
                'view_grade' => $request->has('rules.view_grade'),
            ];
        }

        $expiresAt = null;
        if ($request->filled('expires_value')) {
            $value = (int) $request->expires_value;
            $unit = $request->input('expires_unit');
            $expiresAt = match ($unit) {
                'minutes' => now()->addMinutes($value),
                'hours' => now()->addHours($value),
                'days' => now()->addDays($value),
                default => null,
            };
        }

        $codeModel = AccessCode::create([
            'code' => $code,
            'type' => $request->type,
            'test_id' => $request->type === 'testing' ? $request->test_id : null,
            'resource_url' => $request->type === 'resource' ? $request->resource_url : null,
            'expires_at' => $expiresAt,
            'rules' => $rules,
        ]);

        return redirect()->route('admin.codes.index')
            ->with('success', 'Access Code generated successfully: ' . $code)
            ->with('new_code', $code)
            ->with('new_code_test', $codeModel->test ? $codeModel->test->name : 'Test Session');
    }

    public function destroy(AccessCode $code)
    {
        $code->delete();
        return redirect()->route('admin.codes.index')
            ->with('success', 'Access Code deleted successfully!');
    }

    public function extend10Mins(AccessCode $code)
    {
        $baseTime = ($code->expires_at && $code->expires_at->isFuture()) ? $code->expires_at : now();
        $code->update([
            'expires_at' => $baseTime->addMinutes(10),
        ]);

        return redirect()->route('admin.codes.index')
            ->with('success', 'Access Code ' . $code->code . ' extended by 10 minutes.');
    }

    public function expireNow(AccessCode $code)
    {
        $code->update([
            'expires_at' => now(),
        ]);

        return redirect()->route('admin.codes.index')
            ->with('success', 'Access Code ' . $code->code . ' has been expired.');
    }

    public function analytics(AccessCode $code)
    {
        $code->load(['test']);
        
        // Get all completed student sessions for this access code
        $sessions = \App\Models\TestSession::where('access_code_id', $code->id)
            ->whereNotNull('completed_at')
            ->get();
            
        // Get the blueprint questions
        $blueprintIds = $code->test->question_ids ?? [];
        $questions = \App\Models\Question::whereIn('id', $blueprintIds)->get()->keyBy('id');
        
        // Order questions according to blueprint
        $orderedQuestions = collect($blueprintIds)
            ->map(fn($id) => $questions->get($id))
            ->filter();
            
        // Calculate analytics per question
        $questionAnalytics = [];
        foreach ($orderedQuestions as $index => $q) {
            $correctCount = 0;
            $incorrectCount = 0;
            $emptyCount = 0;
            
            foreach ($sessions as $session) {
                $ans = $session->answers[$q->id] ?? '';
                $correct = $q->correct_answer_string ?? '';
                
                $isEmpty = trim((string)$ans) === '';
                
                $cleanedUser = html_entity_decode(strtolower(trim($ans)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $cleanedCorrect = html_entity_decode(strtolower(trim($correct)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                
                if ($isEmpty) {
                    $emptyCount++;
                } elseif ($cleanedUser === $cleanedCorrect && trim($correct) !== '') {
                    $correctCount++;
                } else {
                    $incorrectCount++;
                }
            }
            
            $total = $sessions->count();
            $successRate = $total > 0 ? round(($correctCount / $total) * 100) : 0;
            
            $questionAnalytics[] = [
                'model' => $q,
                'index' => $index + 1,
                'correct' => $correctCount,
                'incorrect' => $incorrectCount,
                'empty' => $emptyCount,
                'success_rate' => $successRate,
            ];
        }
        
        // Sort questions by difficulty (success rate ascending)
        $troublesomeQuestions = collect($questionAnalytics)->sortBy('success_rate')->values();

        // Calculate average score across all completed sessions
        $avgScore = 0;
        $totalQuestions = count($blueprintIds);
        if ($sessions->isNotEmpty() && $totalQuestions > 0) {
            $avgScore = round($sessions->avg('score'), 1);
        }

        return view('admin.codes.analytics', compact('code', 'sessions', 'orderedQuestions', 'questionAnalytics', 'troublesomeQuestions', 'avgScore', 'totalQuestions'));
    }
}

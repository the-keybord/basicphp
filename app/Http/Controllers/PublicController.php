<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessCode;
use App\Models\TestSession;
use App\Models\Question;
use App\Services\QuestionEngine\QuestionParser;
use App\Services\QuestionEngine\QuestionRenderer;
use App\Services\QuestionEngine\Drivers\QuestionDriverFactory;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    // Show the landing page
    public function index(Request $request)
    {
        if ($request->has('code')) {
            return $this->directJoin($request->query('code'));
        }
        return view('welcome');
    }

    // Direct join with code via link
    public function directJoin(string $code)
    {
        $code = strtoupper($code);
        $codeModel = AccessCode::where('code', $code)->first();

        if (!$codeModel || !$codeModel->isValid()) {
            return redirect()->route('home')->withErrors(['access_code' => 'Invalid or expired code. Please try again.']);
        }

        if ($codeModel->type === 'resource') {
            return redirect()->away($codeModel->resource_url);
        }

        $test = $codeModel->test;
        if (!$test || !$test->is_active) {
            return redirect()->route('home')->withErrors(['access_code' => 'This test is currently deactivated/hidden by the instructor.']);
        }

        return redirect()->route('test.join', ['code' => $code]);
    }

    // Process the 6-character code
    public function accessCode(Request $request)
    {
        $request->validate([
            'access_code' => 'required|string|size:6',
        ]);

        $code = strtoupper($request->access_code);
        $codeModel = AccessCode::where('code', $code)->first();

        if (!$codeModel || !$codeModel->isValid()) {
            return back()->withErrors(['access_code' => 'Invalid or expired code. Please try again.']);
        }

        if ($codeModel->type === 'resource') {
            return redirect()->away($codeModel->resource_url);
        }

        $test = $codeModel->test;
        if (!$test || !$test->is_active) {
            return back()->withErrors(['access_code' => 'This test is currently deactivated/hidden by the instructor.']);
        }

        return redirect()->route('test.join', ['code' => $code]);
    }

    // Show student registration screen for testing session
    public function joinTest(string $code)
    {
        $codeModel = AccessCode::with('test')->where('code', strtoupper($code))->firstOrFail();

        if (!$codeModel->isValid() || $codeModel->type !== 'testing') {
            return redirect()->route('home')->withErrors(['access_code' => 'This code is invalid or expired.']);
        }

        $test = $codeModel->test;
        if (!$test || !$test->is_active) {
            return redirect()->route('home')->withErrors(['access_code' => 'This test is currently deactivated/hidden by the instructor.']);
        }

        return view('welcome.join', compact('codeModel', 'test'));
    }

    // Start testing session, creating TestSession database record
    public function startTest(Request $request, string $code)
    {
        $codeModel = AccessCode::with('test')->where('code', strtoupper($code))->firstOrFail();

        if (!$codeModel->isValid() || $codeModel->type !== 'testing') {
            return redirect()->route('home')->withErrors(['access_code' => 'This code is invalid or expired.']);
        }

        $test = $codeModel->test;
        if (!$test || !$test->is_active) {
            return redirect()->route('home')->withErrors(['access_code' => 'This test is currently deactivated/hidden by the instructor.']);
        }

        $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
        ]);

        $test = $codeModel->test;
        $questionIds = $test->question_ids;

        // Shuffle questions if code rules specify it
        $rules = $codeModel->rules ?? [];
        if (!empty($rules['mix_questions'])) {
            shuffle($questionIds);
        }

        $token = (string) Str::uuid();

        TestSession::create([
            'access_code_id' => $codeModel->id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'token' => $token,
            'questions_order' => $questionIds,
            'answers' => [],
            'score' => null,
            'total_questions' => count($questionIds),
            'started_at' => now(),
        ]);

        return redirect()->route('test.session', ['token' => $token]);
    }

    // Display student test page with questions and ticking timer
    public function showSession(string $token)
    {
        $session = TestSession::with(['accessCode.test'])->where('token', $token)->firstOrFail();

        if ($session->is_interrupted) {
            return redirect()->route('home')->withErrors(['access_code' => 'This test session was interrupted.']);
        }

        if ($session->completed_at) {
            return redirect()->route('test.results', ['token' => $token]);
        }

        // Auto-complete if expired
        if ($session->isExpired()) {
            $this->gradeSession($session, []);
            return redirect()->route('test.results', ['token' => $token]);
        }

        // Fetch questions in the defined session display order
        $questionsMap = Question::whereIn('id', $session->questions_order)->get()->keyBy('id');
        $orderedQuestions = collect($session->questions_order)
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

        // Calculate timer remaining seconds
        $duration = $session->accessCode->test->duration_minutes ?? 45;
        $endTime = $session->started_at->copy()->addMinutes($duration);
        $remainingSeconds = max(0, now()->diffInSeconds($endTime, false));

        return view('welcome.session', compact('session', 'renderedQuestions', 'remainingSeconds'));
    }

    // Submit answers and grade the test session
    public function submitSession(Request $request, string $token)
    {
        $session = TestSession::where('token', $token)->firstOrFail();

        if ($session->is_interrupted) {
            return redirect()->route('home')->withErrors(['access_code' => 'This test session was interrupted.']);
        }

        if ($session->completed_at) {
            return redirect()->route('test.results', ['token' => $token]);
        }

        $submittedAnswers = $request->input('answers', []);
        $this->gradeSession($session, $submittedAnswers);

        return redirect()->route('test.results', ['token' => $token]);
    }

    // Render results view based on rules
    public function showResults(string $token)
    {
        $session = TestSession::with(['accessCode.test'])->where('token', $token)->firstOrFail();

        if ($session->is_interrupted) {
            return redirect()->route('home')->withErrors(['access_code' => 'This test session was interrupted. Results are not available.']);
        }

        if (!$session->completed_at) {
            return redirect()->route('test.session', ['token' => $token]);
        }

        $rules = $session->accessCode->rules ?? [];
        $hideDetails = !empty($rules['hide_after_submit']);
        $viewAnswers = !empty($rules['view_answers_after_submit']);
        $viewCorrect = !empty($rules['view_correct_answers']);

        // Fetch questions in display order
        $questionsMap = Question::whereIn('id', $session->questions_order)->get()->keyBy('id');
        $orderedQuestions = collect($session->questions_order)
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

        return view('welcome.results', compact('session', 'hideDetails', 'viewAnswers', 'viewCorrect', 'renderedQuestions'));
    }

    // Core grading routine
    protected function gradeSession(TestSession $session, array $rawAnswers)
    {
        $score = 0;
        $finalAnswers = [];

        // Fetch questions and check against their correct answer strings
        $questions = Question::whereIn('id', $session->questions_order)->get()->keyBy('id');

        foreach ($session->questions_order as $qId) {
            $question = $questions->get($qId);
            if (!$question) continue;

            $rawValue = $rawAnswers[$qId] ?? '';
            
            // Use Driver factory to normalize user answers
            try {
                $driver = QuestionDriverFactory::make($question->question_type);
                $formattedValue = $driver->formatAnswer($rawValue);
            } catch (\Exception $e) {
                $formattedValue = is_array($rawValue) ? implode(', ', $rawValue) : (string)$rawValue;
            }

            $finalAnswers[$qId] = $formattedValue;

            // Compare case-insensitively and trim spaces
            $cleanedUser = strtolower(trim($formattedValue));
            $cleanedCorrect = strtolower(trim($question->correct_answer_string ?? ''));

            if ($cleanedUser === $cleanedCorrect && $cleanedCorrect !== '') {
                $score++;
            }
        }

        $session->update([
            'score' => $score,
            'answers' => $finalAnswers,
            'completed_at' => now(),
        ]);
    }

    // Auto-save student progress and return session status
    public function autoSave(Request $request, string $token)
    {
        $session = TestSession::where('token', $token)->first();

        if (!$session) {
            return response()->json(['status' => 'not_found'], 404);
        }

        if (!$session->completed_at && !$session->isExpired()) {
            $submittedAnswers = $request->input('answers', []);
            $finalAnswers = $session->answers ?? [];
            $questions = Question::whereIn('id', $session->questions_order)->get()->keyBy('id');

            foreach ($submittedAnswers as $qId => $rawValue) {
                $question = $questions->get($qId);
                if (!$question) continue;

                try {
                    $driver = QuestionDriverFactory::make($question->question_type);
                    $formattedValue = $driver->formatAnswer($rawValue);
                } catch (\Exception $e) {
                    $formattedValue = is_array($rawValue) ? implode(', ', $rawValue) : (string)$rawValue;
                }

                $finalAnswers[$qId] = $formattedValue;
            }

            $session->update([
                'answers' => $finalAnswers,
            ]);
        }

        return response()->json([
            'status' => $session->completed_at ? 'completed' : 'active',
            'is_interrupted' => (bool) $session->is_interrupted,
        ]);
    }
}
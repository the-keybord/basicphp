<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestSession extends Model
{
    protected $fillable = [
        'access_code_id',
        'firstname',
        'lastname',
        'token',
        'questions_order',
        'answers',
        'score',
        'total_questions',
        'started_at',
        'completed_at',
        'is_interrupted'
    ];

    protected $casts = [
        'questions_order' => 'array',
        'answers' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_interrupted' => 'boolean',
    ];

    public function accessCode(): BelongsTo
    {
        return $this->belongsTo(AccessCode::class);
    }

    /**
     * Checks if the test session is still within its time limit.
     */
    public function isExpired(): bool
    {
        if ($this->completed_at) {
            return true;
        }

        $duration = $this->accessCode->test->duration_minutes ?? 45;
        $endTime = $this->started_at->copy()->addMinutes($duration);

        return now()->isAfter($endTime);
    }

    /**
     * Recalculates and updates the score based on the current correct answers.
     */
    public function recalculateScore(): int
    {
        $questions = Question::whereIn('id', $this->questions_order)->get()->keyBy('id');
        $score = 0;
        $answers = $this->answers ?? [];

        foreach ($this->questions_order as $qId) {
            $question = $questions->get($qId);
            if (!$question) continue;

            $userVal = $answers[$qId] ?? '';
            $cleanedUser = html_entity_decode(strtolower(trim((string)$userVal)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $cleanedCorrect = html_entity_decode(strtolower(trim((string)$question->correct_answer_string)), ENT_QUOTES | ENT_HTML5, 'UTF-8');

            if ($cleanedUser === $cleanedCorrect && $cleanedCorrect !== '') {
                $score++;
            }
        }

        if ($this->score !== $score) {
            $this->update(['score' => $score]);
        }

        return $score;
    }
}

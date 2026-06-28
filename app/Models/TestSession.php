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
}

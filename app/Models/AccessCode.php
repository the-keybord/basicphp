<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessCode extends Model
{
    protected $fillable = ['code', 'test_id', 'expires_at', 'rules'];

    protected $casts = [
        'rules' => 'array',
        'expires_at' => 'datetime',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    /**
     * Checks if the access code is currently active and not expired.
     */
    public function isValid(): bool
    {
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }
        return true;
    }
}

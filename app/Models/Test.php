<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = ['name', 'question_ids', 'duration_minutes', 'is_active'];

    protected $casts = [
        'question_ids' => 'array',
        'is_active' => 'boolean',
    ];

    public function accessCodes(): HasMany
    {
        return $this->hasMany(AccessCode::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    protected $fillable = ['name', 'question_ids'];

    protected $casts = [
        'question_ids' => 'array',
    ];

    public function accessCodes(): HasMany
    {
        return $this->hasMany(AccessCode::class);
    }
}

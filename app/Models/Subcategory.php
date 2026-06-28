<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subcategory extends Model
{
    protected $fillable = ['category_id', 'name', 'default_test_size', 'default_test_time'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function primaryQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'primary_subcategory_id');
    }

    public function secondaryQuestions(): HasMany
    {
        return $this->hasMany(Question::class, 'secondary_subcategory_id');
    }
}

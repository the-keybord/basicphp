<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'primary_subcategory_id',
        'secondary_subcategory_id',
        'question_type',
        'xml_content',
        'correct_answer_string',
        'sibling_group_id'
    ];

    public function primarySubcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'primary_subcategory_id');
    }

    public function secondarySubcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class, 'secondary_subcategory_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'subcategory',
        'question_type',
        'xml_content',
        'correct_answer_string'
    ];
}

<?php

namespace App\Services\QuestionEngine\Drivers;

use InvalidArgumentException;

class QuestionDriverFactory
{
    /**
     * Instantiates a question driver for a given question type.
     */
    public static function make(string $type): QuestionDriverInterface
    {
        return match ($type) {
            'singleselect' => new SingleSelectDriver(),
            'multiselect'  => new MultiSelectDriver(),
            'truefalse'    => new TrueFalseDriver(),
            'dropdown'     => new DropdownDriver(),
            'drag_and_drop' => new DragAndDropDriver(),
            default => throw new InvalidArgumentException("Unknown question type: {$type}"),
        };
    }
}

<?php

namespace App\Services\QuestionEngine\Drivers;

class DragAndDropDriver implements QuestionDriverInterface
{
    public function getValidationRules(): array
    {
        return [];
    }

    public function formatAnswer(mixed $value): string
    {
        if (is_array($value)) {
            return implode(', ', array_map(function ($val) {
                return trim((string)$val);
            }, $value));
        }

        return trim((string)$value);
    }
}

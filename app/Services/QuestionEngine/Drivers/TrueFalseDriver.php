<?php

namespace App\Services\QuestionEngine\Drivers;

class TrueFalseDriver implements QuestionDriverInterface
{
    public function getValidationRules(): array
    {
        return [];
    }

    public function formatAnswer(mixed $value): string
    {
        if (is_array($value)) {
            // Yes, No, Yes style
            return implode(', ', array_map(function ($val) {
                return ucfirst(strtolower(trim((string)$val)));
            }, $value));
        }

        return ucfirst(strtolower(trim((string)$value)));
    }
}

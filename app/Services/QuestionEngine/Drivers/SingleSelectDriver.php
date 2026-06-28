<?php

namespace App\Services\QuestionEngine\Drivers;

class SingleSelectDriver implements QuestionDriverInterface
{
    public function getValidationRules(): array
    {
        return [];
    }

    public function formatAnswer(mixed $value): string
    {
        if (is_array($value)) {
            return trim((string) reset($value));
        }
        return trim((string) $value);
    }
}

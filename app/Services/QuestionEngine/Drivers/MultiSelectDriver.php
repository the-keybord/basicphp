<?php

namespace App\Services\QuestionEngine\Drivers;

class MultiSelectDriver implements QuestionDriverInterface
{
    public function getValidationRules(): array
    {
        return [];
    }

    public function formatAnswer(mixed $value): string
    {
        if (is_array($value)) {
            // Sort to ensure consistency (e.g. [1, 4] vs [4, 1])
            $sorted = array_map('intval', $value);
            sort($sorted);
            return implode(', ', $sorted);
        }

        if (is_string($value)) {
            $parts = array_filter(array_map('trim', explode(',', $value)));
            $sorted = array_map('intval', $parts);
            sort($sorted);
            return implode(', ', $sorted);
        }

        return '';
    }
}

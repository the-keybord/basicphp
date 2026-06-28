<?php

namespace App\Services\QuestionEngine\Drivers;

interface QuestionDriverInterface
{
    /**
     * Get validation rules for this question type.
     */
    public function getValidationRules(): array;

    /**
     * Format the answer payload from the frontend/user response into standard database format.
     */
    public function formatAnswer(mixed $value): string;
}

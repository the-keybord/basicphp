<?php

namespace App\Services\QuestionEngine;

class QuestionParser
{
    public function parse(string $xml): array
    {
        libxml_use_internal_errors(true);

        $question = simplexml_load_string($xml);

        if (!$question) {
            throw new \Exception("Invalid XML");
        }

        return [
            'text' => trim((string)$question->text),

            'options' => collect($question->option)
                ->map(fn($option) => trim((string)$option))
                ->toArray(),
        ];
    }
}
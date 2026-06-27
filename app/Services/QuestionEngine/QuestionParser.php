<?php

namespace App\Services\QuestionEngine;

class QuestionParser
{
    public function parse(string $xml): array
    {
        libxml_use_internal_errors(true);

        // Wrap in a root element if it doesn't have one to prevent invalid XML exceptions
        if (!str_starts_with(trim($xml), '<')) {
            $xml = '<question><text>' . $xml . '</text></question>';
        }

        $question = @simplexml_load_string($xml);

        if (!$question) {
            // Let's try parsing it by wrapping it in a root element in case it's a snippet
            $xmlWrapped = '<question>' . $xml . '</question>';
            $question = @simplexml_load_string($xmlWrapped);
            if (!$question) {
                throw new \Exception("Invalid XML structure");
            }
        }

        $text = '';
        if (isset($question->text)) {
            $text = trim((string)$question->text);
        }

        $options = [];
        if (isset($question->option)) {
            foreach ($question->option as $option) {
                if ($option->children()->count() > 0) {
                    $optArray = [];
                    foreach ($option->children() as $child) {
                        $optArray[$child->getName()] = trim((string)$child);
                    }
                    $options[] = $optArray;
                } else {
                    $options[] = trim((string)$option);
                }
            }
        }

        return [
            'text' => $text,
            'options' => $options,
        ];
    }
}
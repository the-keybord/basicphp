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

        // Support for yesno/matrix question subjects
        $subjects = [];
        if (isset($question->subjects->subject)) {
            foreach ($question->subjects->subject as $sub) {
                if ($sub->children()->count() > 0) {
                    $subOpts = [];
                    foreach ($sub->children() as $child) {
                        $subOpts[] = trim((string)$child);
                    }
                    $subjects[] = $subOpts;
                } else {
                    $subjects[] = trim((string)$sub);
                }
            }
        } elseif (isset($question->subject)) {
            foreach ($question->subject as $sub) {
                if ($sub->children()->count() > 0) {
                    $subOpts = [];
                    foreach ($sub->children() as $child) {
                        $subOpts[] = trim((string)$child);
                    }
                    $subjects[] = $subOpts;
                } else {
                    $subjects[] = trim((string)$sub);
                }
            }
        }

        // Support for extracting primary image tag
        $image = '';
        if (isset($question->image)) {
            $imgVal = trim((string)$question->image);
            if (!empty($imgVal) && strtolower($imgVal) !== 'url') {
                $image = $imgVal;
            }
        }

        return [
            'text' => $text,
            'options' => $options,
            'subjects' => $subjects,
            'image' => $image,
        ];
    }
}
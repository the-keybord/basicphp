<?php

namespace App\Services\QuestionEngine;

class QuestionRenderer
{
    protected ImageRenderer $images;

    public function __construct()
    {
        $this->images = new ImageRenderer();
    }

    public function render(array $question): array
    {
        $question['text'] =
            $this->images->render($question['text']);

        $question['options'] =
            collect($question['options'])
                ->map(fn($o) => $this->images->render($o))
                ->toArray();

        return $question;
    }
}
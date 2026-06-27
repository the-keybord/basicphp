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
        $question['text'] = $this->images->render($question['text']);

        $question['options'] = collect($question['options'])
            ->map(function ($o) {
                if (is_array($o)) {
                    return collect($o)->map(fn($val) => $this->images->render($val))->toArray();
                }
                return $this->images->render($o);
            })
            ->toArray();

        $question['subjects'] = collect($question['subjects'] ?? [])
            ->map(function ($s) {
                if (is_array($s)) {
                    return collect($s)->map(fn($val) => $this->images->render($val))->toArray();
                }
                return $this->images->render($s);
            })
            ->toArray();

        return $question;
    }
}
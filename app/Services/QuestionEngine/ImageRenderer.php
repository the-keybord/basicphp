<?php

namespace App\Services\QuestionEngine;

class ImageRenderer
{
    public function render(string $text): string
    {
        return preg_replace_callback(
            "/@img\('([^']+)'\)/",
            function ($matches) {

                $url = asset('storage/questions/' . $matches[1]);

                return "<img src=\"$url\" class=\"rounded my-3 max-w-full\">";
            },
            nl2br(e($text))
        );
    }
}
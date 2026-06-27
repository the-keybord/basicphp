<?php

namespace App\Services\QuestionEngine;

class ImageRenderer
{
    public function render(string $text): string
    {
        return preg_replace_callback(
            "/@img\('([^']+)'\)/",
            function ($matches) {

                $disk = config('filesystems.default') === 's3' ? 's3' : 'public';
                $url = \Illuminate\Support\Facades\Storage::disk($disk)->url('questions/' . $matches[1]);

                return "<img src=\"$url\" class=\"rounded my-3 max-w-full\">";
            },
            nl2br(e($text))
        );
    }
}
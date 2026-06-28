<?php

namespace App\Services\QuestionEngine;

class ImageRenderer
{
    public function render(string $text): string
    {
        // Split the text on @img('...') tokens so we can:
        //   - HTML-encode + nl2br the plain text parts safely
        //   - Replace the tokens with <img> tags (using signed URLs)
        // Doing e() before the regex would encode the quotes and break matching.
        $parts = preg_split("/@img\('([^']+)'\)/", $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        $output = '';
        foreach ($parts as $i => $part) {
            if ($i % 2 === 0) {
                // Plain text segment — HTML-encode only; newlines preserved via CSS white-space: pre-wrap
                $output .= e($part);
            } else {
                // $part is the captured filename inside @img('...')
                $path = 'questions/' . $part;

                $url = route('image.proxy', ['filename' => $part], false);

                $output .= "<img src=\"$url\" class=\"rounded my-3\" style=\"max-width: 600px; height: auto; display: block;\">";
            }
        }

        return $output;
    }
}
<?php

namespace App\Services\QuestionEngine;

class ImageRenderer
{
    public function render(string $text): string
    {
        // Translate legacy @img('filename') into inline <image>filename</image> tags for uniform parsing.
        $text = preg_replace("/@img\('([^']+)'\)/", '<image>$1</image>', $text);

        // Escape raw ampersands that are not part of an existing XML/HTML entity
        $text = preg_replace('/&(?![A-Za-z0-9#]+;)/', '&amp;', $text);

        // Load into DOMDocument
        $dom = new \DOMDocument();
        
        // Wrap the text in a root element
        $wrappedText = '<root>' . $text . '</root>';
        
        // Suppress warnings for invalid XML/HTML tags
        libxml_use_internal_errors(true);
        $dom->loadXML('<?xml version="1.0" encoding="utf-8"?>' . $wrappedText, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();

        $root = $dom->documentElement;
        if (!$root) {
            return e($text);
        }

        return $this->renderDomNode($root);
    }

    protected function renderDomNode(\DOMNode $node): string
    {
        if ($node instanceof \DOMText) {
            return e($node->textContent);
        }

        if ($node instanceof \DOMElement) {
            $tagName = strtolower($node->tagName);

            if ($tagName === 'root') {
                $inner = '';
                foreach ($node->childNodes as $child) {
                    $inner .= $this->renderDomNode($child);
                }
                return $inner;
            }

            if ($tagName === 'strong' || $tagName === 'bold' || $tagName === 'b') {
                $inner = '';
                foreach ($node->childNodes as $child) {
                    $inner .= $this->renderDomNode($child);
                }
                return '<strong>' . $inner . '</strong>';
            }

            if ($tagName === 'u') {
                $inner = '';
                foreach ($node->childNodes as $child) {
                    $inner .= $this->renderDomNode($child);
                }
                return '<u>' . $inner . '</u>';
            }

            if ($tagName === 'i') {
                $inner = '';
                foreach ($node->childNodes as $child) {
                    $inner .= $this->renderDomNode($child);
                }
                return '<i>' . $inner . '</i>';
            }

            if ($tagName === 'br') {
                return '<br>';
            }

            if ($tagName === 'p') {
                $inner = '';
                foreach ($node->childNodes as $child) {
                    $inner .= $this->renderDomNode($child);
                }
                return '<p>' . $inner . '</p>';
            }

            if ($tagName === 'image') {
                $filename = trim($node->textContent);
                if (str_starts_with($filename, 'questions/')) {
                    $filename = substr($filename, 10);
                }
                $url = route('image.proxy', ['filename' => $filename], false);
                return '<img src="' . e($url) . '" class="rounded inline-block align-middle my-2" style="max-height: 400px; max-width: 100%;">';
            }

            if ($tagName === 'img') {
                $src = $node->getAttribute('src');
                if (!str_starts_with($src, 'http') && !str_starts_with($src, 'data:')) {
                    $filename = basename($src);
                    $url = route('image.proxy', ['filename' => $filename], false);
                } else {
                    $url = $src;
                }
                return '<img src="' . e($url) . '" class="rounded inline-block align-middle my-2" style="max-height: 400px; max-width: 100%;">';
            }

            // Fallback for other tags — strip the tag itself but process children recursively
            $inner = '';
            foreach ($node->childNodes as $child) {
                $inner .= $this->renderDomNode($child);
            }
            return $inner;
        }

        return '';
    }
}
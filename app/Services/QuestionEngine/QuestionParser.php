<?php

namespace App\Services\QuestionEngine;

class QuestionParser
{
    protected function getInnerXML(\SimpleXMLElement $element): string
    {
        $dom = dom_import_simplexml($element);
        $inner = '';
        foreach ($dom->childNodes as $child) {
            $inner .= $dom->ownerDocument->saveXML($child);
        }
        return trim($inner);
    }

    protected function isStructured(\SimpleXMLElement $element): bool
    {
        if ($element->children()->count() === 0) {
            return false;
        }
        $inlineTags = ['strong', 'bold', 'b', 'u', 'i', 'br', 'image', 'img'];
        foreach ($element->children() as $child) {
            if (!in_array(strtolower($child->getName()), $inlineTags)) {
                return true;
            }
        }
        return false;
    }

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
            $text = $this->getInnerXML($question->text);
        }

        // Also capture any @img() tokens sitting as raw text nodes directly inside
        // <question> but outside of <text> (e.g. placed between </text> and <option>).
        // Strip all XML tags from the raw input and search for floating @img() tokens.
        $rawStripped = preg_replace('/<[^>]+>/', '', $xml);
        preg_match_all("/@img\('[^']+'\)/", $rawStripped, $floatingImgs);
        if (!empty($floatingImgs[0])) {
            $text .= "\n" . implode("\n", $floatingImgs[0]);
        }

        $options = [];
        $optionElements = null;

        if (isset($question->options->option)) {
            $optionElements = $question->options->option;
        } elseif (isset($question->option)) {
            $optionElements = $question->option;
        }

        if ($optionElements) {
            foreach ($optionElements as $option) {
                if ($this->isStructured($option)) {
                    $optArray = [];
                    foreach ($option->children() as $child) {
                        $optArray[$child->getName()] = $this->getInnerXML($child);
                    }
                    $options[] = $optArray;
                } else {
                    $options[] = $this->getInnerXML($option);
                }
            }
        }

        // Support for yesno/matrix question subjects
        $subjects = [];
        if (isset($question->subjects->subject)) {
            foreach ($question->subjects->subject as $sub) {
                if ($this->isStructured($sub)) {
                    $subOpts = [];
                    foreach ($sub->children() as $child) {
                        $subOpts[] = $this->getInnerXML($child);
                    }
                    $subjects[] = $subOpts;
                } else {
                    $subjects[] = $this->getInnerXML($sub);
                }
            }
        } elseif (isset($question->subject)) {
            foreach ($question->subject as $sub) {
                if ($this->isStructured($sub)) {
                    $subOpts = [];
                    foreach ($sub->children() as $child) {
                        $subOpts[] = $this->getInnerXML($child);
                    }
                    $subjects[] = $subOpts;
                } else {
                    $subjects[] = $this->getInnerXML($sub);
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
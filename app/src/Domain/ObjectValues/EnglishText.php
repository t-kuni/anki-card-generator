<?php

namespace TKuni\AnkiCardGenerator\Domain\ObjectValues;

class EnglishText
{
    /**
     * @var string
     */
    private $text;

    public function __construct(string $text)
    {
        $this->text = trim($text);
    }

    public function separate() : array
    {
        $texts = preg_split('/(\.\s|\.$|\?)/', $this->text, -1, PREG_SPLIT_NO_EMPTY);

        $trimedTexts = array_map(function($text) {
            return trim($text);
        }, $texts);

        $filteredTexts = array_filter($trimedTexts, function($trimed) {
            return !empty($trimed);
        });

        return $filteredTexts;
    }
}
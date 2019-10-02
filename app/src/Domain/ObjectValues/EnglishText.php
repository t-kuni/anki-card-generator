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
        $this->text = $text;
    }

    public function separate() : array
    {
        $texts = explode('.?', $this->text);

        return array_map(function($text) {
            return $text;
        }, $texts);
    }
}
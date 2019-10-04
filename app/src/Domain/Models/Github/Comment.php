<?php


namespace TKuni\AnkiCardGenerator\Domain\Models\Github;


use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;

class Comment
{
    /**
     * @var EnglishText
     */
    private $body;

    public function __construct(EnglishText $body)
    {
        $this->body = $body;
    }

    public function body() {
        return $this->body;
    }
}
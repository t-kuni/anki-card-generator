<?php

namespace TKuni\AnkiCardGenerator\Domain\Models;

class Card
{
    /**
     * @var string
     */
    private $front;
    /**
     * @var string
     */
    private $back;

    public function __construct(string $front, string $back)
    {
        $this->front = $front;
        $this->back = $back;
    }

    public function front() {
        return $this->front;
    }

    public function back() {
        return $this->back;
    }
}
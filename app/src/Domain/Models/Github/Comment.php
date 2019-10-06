<?php


namespace TKuni\AnkiCardGenerator\Domain\Models\Github;


use Carbon\Carbon;
use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;

class Comment
{
    /**
     * @var EnglishText
     */
    private $body;
    /**
     * @var Carbon
     */
    private $created_at;

    public function __construct(EnglishText $body, Carbon $created_at)
    {
        $this->body       = $body;
        $this->created_at = $created_at;
    }

    public function body()
    {
        return $this->body;
    }

    public function created_at()
    {
        return $this->created_at;
    }
}
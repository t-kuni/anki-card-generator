<?php


namespace TKuni\AnkiCardGenerator\Domain\Models\Github;


use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;

class Issue
{
    /**
     * @var int
     */
    private $number;
    /**
     * @var EnglishText
     */
    private $title;
    /**
     * @var EnglishText
     */
    private $body;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $repository;

    public function __construct(string $username, string $repository, int $number, EnglishText $title,
                                EnglishText $body)
    {
        $this->number     = $number;
        $this->title      = $title;
        $this->body       = $body;
        $this->username   = $username;
        $this->repository = $repository;
    }

    public function number() {
        return $this->number;
    }

    public function username() {
        return $this->username;
    }

    public function repository() {
        return $this->repository;
    }

    public function title() {
        return $this->title;
    }

    public function body() {
        return $this->body;
    }
}
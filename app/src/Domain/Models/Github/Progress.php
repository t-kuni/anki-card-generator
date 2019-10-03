<?php


namespace TKuni\AnkiCardGenerator\Domain\Models\Github;


use Carbon\Carbon;

class Progress
{
    /**
     * @var Carbon
     */
    private $checked_at;

    public function __construct(Carbon $checked_at)
    {
        $this->checked_at = $checked_at;
    }

    public function checked_at() {
        return $this->checked_at;
    }
}
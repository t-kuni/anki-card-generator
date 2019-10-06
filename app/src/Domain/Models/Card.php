<?php

namespace TKuni\AnkiCardGenerator\Domain\Models;

class Card implements \JsonSerializable
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
        $this->back  = $back;
    }

    public function front()
    {
        return $this->front;
    }

    public function back()
    {
        return $this->back;
    }

    public function __debugInfo()
    {
        return (string)((array)$this);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'front' => $this->front,
            'back'  => $this->back
        ];
    }
}
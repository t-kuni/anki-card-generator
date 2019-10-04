<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

use TKuni\AnkiCardGenerator\Domain\Models\Card;

interface IAnkiWebAdapter
{
    public function login($id, $pw);

    public function saveCard($deck, Card $card);
}
<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

interface IAnkiWebAdapter
{
    public function login($id, $pw);

    public function saveCard($deck, $front, $back);
}
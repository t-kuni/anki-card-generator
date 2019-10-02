<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

interface IAnkiWebAdapter
{
    public function addCard($deck, $front, $back);
}
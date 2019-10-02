<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

interface IAnkiWebAdapter
{
    public function createCard($deck, $front, $back);
}
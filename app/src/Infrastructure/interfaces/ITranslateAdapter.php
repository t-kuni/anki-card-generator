<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

interface ITranslateAdapter
{
    public function translate(string $text): string;
}
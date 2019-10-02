<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


use Github\Client;
use Stichoza\GoogleTranslate\GoogleTranslate;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Comment;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\ITranslateAdapter;

class TranslateAdapter implements ITranslateAdapter
{
    /**
     * @var GoogleTranslate
     */
    private $translater;

    public function __construct()
    {
        $tr = new GoogleTranslate();
        $tr->setSource('en');
        $tr->setTarget('ja');
        $this->translater = $tr;
    }

    public function translate(string $text): string
    {
        return $this->translater->translate($text);
    }
}
<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


use Github\Client;
use Psr\Log\LoggerInterface;
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
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $tr = new GoogleTranslate();
        $tr->setSource('en');
        $tr->setTarget('ja');
        $this->translater = $tr;

        $this->logger = $logger;
    }

    public function translate(string $text): string
    {
        $this->logger->info('Start to translate', func_get_args());

        $result = $this->translater->translate($text);

        $this->logger->info('End to translate');

        return $result;
    }
}
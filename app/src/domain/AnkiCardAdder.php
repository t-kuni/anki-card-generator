<?php

namespace TKuni\AnkiCardGenerator\Domain;

use Dotenv\Dotenv;
use Nesk\Puphpeteer\Puppeteer;
use Nesk\Rialto\Data\JsFunction;
use SimpleLog\Logger;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;

class AnkiCardAdder {
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var IAnkiWebAdapter
     */
    private $ankiWebAdapter;

    public function __construct(\Psr\Log\LoggerInterface $logger, IAnkiWebAdapter $ankiWebAdapter)
    {
        $this->logger = $logger;
        $this->ankiWebAdapter = $ankiWebAdapter;
    }

    public function addCard($deck, $front, $back) {
        $this->ankiWebAdapter->addCard($deck, $front, $back);
    }
}
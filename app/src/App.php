<?php

namespace TKuni\AnkiCardGenerator;

use Dotenv\Dotenv;
use SimpleLog\Logger;
use TKuni\AnkiCardGenerator\Domain\AnkiCardAdder;
use TKuni\AnkiCardGenerator\Infrastructure\AnkiWebAdapter;

class App {

    public function __construct()
    {
        $this->logger = new Logger('/dev/stdout', 'default');

        $dotenv = Dotenv::create(__DIR__ . '/../');
        $dotenv->load();

        $ankiWebAdapter = new AnkiWebAdapter($this->logger);
        $this->ankiCardAdder = new AnkiCardAdder($this->logger, $ankiWebAdapter);
    }

    public function run() {
        $this->ankiCardAdder->addCard('000_test', 'test', 'test');
    }
}
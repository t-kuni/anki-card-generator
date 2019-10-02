<?php

namespace TKuni\AnkiCardGenerator;

use Dotenv\Dotenv;
use SimpleLog\Logger;
use TKuni\AnkiCardGenerator\Domain\AnkiCardAdder;
use TKuni\AnkiCardGenerator\Infrastructure\AnkiWebAdapter;

class App {

    /**
     * @var AnkiWebAdapter
     */
    private $ankiWebAdapter;

    public function __construct()
    {
        $this->logger = new Logger('/dev/stdout', 'default');

        $dotenv = Dotenv::create(__DIR__ . '/../');
        $dotenv->load();

        $this->ankiWebAdapter = new AnkiWebAdapter($this->logger);
    }

    public function run() {
        $id = getenv('ANKI_WEB_ID');
        $pw = getenv('ANKI_WEB_PW');
        $this->ankiWebAdapter->login($id, $pw);
        $this->ankiWebAdapter->createCard('000_test', 'test', 'test');
    }
}
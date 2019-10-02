<?php

namespace TKuni\AnkiCardGenerator;

use Dotenv\Dotenv;
use SimpleLog\Logger;
use Stichoza\GoogleTranslate\GoogleTranslate;
use TKuni\AnkiCardGenerator\Domain\AnkiCardAdder;
use TKuni\AnkiCardGenerator\Infrastructure\AnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\TranslateAdapter;

class App {

    /**
     * @var AnkiWebAdapter
     */
    private $ankiWebAdapter;
    /**
     * @var GithubAdapter
     */
    private $githubAdapter;

    public function __construct()
    {
        $this->logger = new Logger('/dev/stdout', 'default');

        $dotenv = Dotenv::create(__DIR__ . '/../');
        $dotenv->load();

        $this->ankiWebAdapter = new AnkiWebAdapter($this->logger);
        $this->githubAdapter = new GithubAdapter();
        $this->translateAdapter = new TranslateAdapter();
    }

    public function run() {

        $username   = getenv('GITHUB_USER');
        $repository = getenv('GITHUB_REPO');
        $issues     = $this->githubAdapter->fetchIssues($username, $repository);

        $issue  = $issues[0];

        $comments = $this->githubAdapter->fetchComments($issue);

        $texts = $issue->title()->separate();

        $this->translateAdapter->translate($texts[0]);

//        $id = getenv('ANKI_WEB_ID');
//        $pw = getenv('ANKI_WEB_PW');
//        $this->ankiWebAdapter->login($id, $pw);
//        $this->ankiWebAdapter->createCard('000_test', 'test', 'test');
    }
}
<?php

namespace TKuni\AnkiCardGenerator;

use Dotenv\Dotenv;
use SimpleLog\Logger;
use Stichoza\GoogleTranslate\GoogleTranslate;
use TKuni\AnkiCardGenerator\Domain\AnkiCardAdder;
use TKuni\AnkiCardGenerator\Domain\Models\Card;
use TKuni\AnkiCardGenerator\Infrastructure\AnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\ProgressRepository;
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
    /**
     * @var TranslateAdapter
     */
    private $translateAdapter;
    /**
     * @var ProgressRepository
     */
    private $progressRepo;

    public function __construct()
    {
        $this->logger = new Logger('/dev/stdout', 'default');

        $dotenv = Dotenv::create(__DIR__ . '/../');
        $dotenv->load();

        $this->ankiWebAdapter = new AnkiWebAdapter($this->logger);
        $this->githubAdapter = new GithubAdapter();
        $this->translateAdapter = new TranslateAdapter();
        $this->progressRepo = new ProgressRepository();
    }

    public function run() {

        $username   = getenv('GITHUB_USER');
        $repository = getenv('GITHUB_REPO');
        $issues     = $this->githubAdapter->fetchIssues($username, $repository);

        $enTexts = [];

        $issue  = $issues[0];
        $since = null;
        if (($progress = $this->progressRepo->findByIssue($issue->username(), $issue->repository(), $issue->number())) !== null) {
            $since = $progress->checked_at();
        } else {
            $enTexts += $issue->title()->separate();
            $enTexts += $issue->body()->separate();
        }

        $comments = $this->githubAdapter->fetchComments($issue, $since);
        $enTexts += array_map(function($comment) {
            return $comment->body()->separate();
        }, $comments);

        $translateAdapter = $this->translateAdapter;

        $cards = array_map(function($enText) use ($translateAdapter) {
            $jpText = $translateAdapter->translate($enText);
            return new Card($enText, $jpText);
        }, $enTexts);

        $id = getenv('ANKI_WEB_ID');
        $pw = getenv('ANKI_WEB_PW');
        $this->ankiWebAdapter->login($id, $pw);
        foreach ($cards as $card) {
            $this->ankiWebAdapter->saveCard('000_test', $card);
        }
    }
}
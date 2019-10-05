<?php

namespace TKuni\AnkiCardGenerator;

use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Domain\AnkiCardAdder;
use TKuni\AnkiCardGenerator\Domain\Models\Card;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\ITranslateAdapter;

class App
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var IAnkiWebAdapter
     */
    private $ankiWeb;
    /**
     * @var IGithubAdapter
     */
    private $github;
    /**
     * @var ITranslateAdapter
     */
    private $translate;
    /**
     * @var IProgressRepository
     */
    private $progressRepo;

    public function __construct(LoggerInterface $logger, IAnkiWebAdapter $ankiWeb, IGithubAdapter $github,
                                ITranslateAdapter $translate,
                                IProgressRepository $progressRepo)
    {
        $this->logger       = $logger;
        $this->ankiWeb      = $ankiWeb;
        $this->github       = $github;
        $this->translate    = $translate;
        $this->progressRepo = $progressRepo;
    }

    public function run()
    {
        $username   = getenv('GITHUB_USER');
        $repository = getenv('GITHUB_REPO');
        $issues     = $this->github->fetchIssues($username, $repository);

        $enTexts = [];

        $issue = $issues[0];
        $since = null;
        if (($progress = $this->progressRepo->findByIssue($issue->username(), $issue->repository(), $issue->number())) !== null) {
            $since = $progress->checked_at();
        } else {
            $enTexts = array_merge($enTexts, $issue->title()->separate());
            $enTexts = array_merge($enTexts, $issue->body()->separate());
        }

        $comments = $this->github->fetchComments($issue, $since);
        $enTexts  = array_merge(
            $enTexts,
            array_reduce($comments, function ($curr, $comment) {
                return array_merge($curr, $comment->body()->separate());
            }, [])
        );

        $translate = $this->translate;

        $cards = array_map(function ($enText) use ($translate) {
            $jpText = $translate->translate($enText);
            return new Card($enText, $jpText);
        }, $enTexts);

        $id   = getenv('ANKI_WEB_ID');
        $pw   = getenv('ANKI_WEB_PW');
        $deck = getenv('ANKI_WEB_DECK');
        $this->ankiWeb->login($id, $pw);
        foreach ($cards as $card) {
            $this->ankiWeb->saveCard($deck, $card);
        }

        $created_at = null;
        if (!empty($comments[count($comments) - 1]->created_at())) {
            $created_at = $comments[count($comments) - 1]->created_at();
        }
        $this->progressRepo->save($username, $repository, $issue->number(), $created_at);
    }
}
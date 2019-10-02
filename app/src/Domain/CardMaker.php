<?php

namespace TKuni\AnkiCardGenerator\domain;

use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;

class CardMaker
{
    /**
     * @var IGithubAdapter
     */
    private $githubAdapter;

    public function __construct(IGithubAdapter $githubAdapter)
    {
        $this->githubAdapter = $githubAdapter;
    }

    public function makeCard()
    {
        $username   = getenv('GITHUB_USER');
        $repository = getenv('GITHUB_REPO');
        $issues     = $this->githubAdapter->fetchIssues($username, $repository);

        $issue  = $issues[0];
        $title  = $issue['title'];
        $body   = $issue['body'];
        $number = $issue['number'];

        $comments = $this->githubAdapter->fetchComments($username, $repository, $number);
    }
}
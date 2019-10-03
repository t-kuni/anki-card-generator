<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


use Carbon\Carbon;
use Github\Client;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Comment;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;

class GithubAdapter implements IGithubAdapter
{
    public function __construct()
    {
        $this->client = new Client();
    }

    public function fetchIssues(string $username, string $repository): array
    {
        $issues = $this->client->api('issue')->all($username, $repository, [
            'state'  => 'all',
            'filter' => 'all',
            'sort'   => 'updated',
        ]);

        return array_map(function ($issue) use ($username, $repository) {
            $title  = new EnglishText($issue['title']);
            $body   = new EnglishText($issue['body']);
            $number = $issue['number'];
            return new Issue($username, $repository, $number, $title, $body);
        }, $issues);
    }

    public function fetchComments(Issue $issue, ?Carbon $since): array
    {
        $comments = $this->client->api('issue')
            ->comments()
            ->all($issue->username(), $issue->repository(), $issue->number());

        return array_map(function ($comment) {
            $body = new EnglishText($comment['body']);
            return new Comment($body);
        }, $comments);
    }
}
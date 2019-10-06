<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


use Carbon\Carbon;
use Github\Client;
use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Comment;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;

class GithubAdapter implements IGithubAdapter
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Client
     */
    private $client;

    public function __construct(LoggerInterface $logger)
    {
        $this->client = new Client();
        $this->logger = $logger;
    }

    public function fetchIssues(string $username, string $repository): array
    {
        $this->logger->info('Start to fetch Issue from github', func_get_args());

        $issues = $this->client->api('issue')->all($username, $repository, [
            'state'  => 'all',
            'filter' => 'all',
            'sort'   => 'updated',
        ]);

        $result = array_map(function ($issue) use ($username, $repository) {
            $title  = new EnglishText($issue['title']);
            $body   = new EnglishText($issue['body']);
            $number = $issue['number'];
            return new Issue($username, $repository, $number, $title, $body);
        }, $issues);

        $this->logger->info('End to fetch Issue from github');

        return $result;
    }

    public function fetchComments(Issue $issue, ?Carbon $since): array
    {
        $this->logger->info('Start to fetch Comments from github', func_get_args());

        $parameter = [];
        if (!empty($since)) {
            $parameter['since'] = $since->format(\DateTime::ISO8601);
        }

        $comments = $this->client->api('issue')
            ->comments()
            ->all($issue->username(), $issue->repository(), $issue->number(), $parameter);

        $result = array_map(function ($comment) {
            $body       = new EnglishText($comment['body']);
            $created_at = Carbon::parse($comment['updated_at']);
            return new Comment($body, $created_at);
        }, $comments);

        $this->logger->info('End to fetch Comments from github');

        return $result;
    }
}
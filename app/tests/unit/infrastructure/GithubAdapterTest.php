<?php

namespace tests\unit\infrastructure;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;

class GithubAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function fetchIssues() {
        $adapter = new GithubAdapter();
        $issues = $adapter->fetchIssues('laravel', 'framework');
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function fetchComments_shouldGetAllCommentsIfNotSpecifySince() {
        #
        # Prepare
        #
        $logger = \Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('info');
        app()->bind(LoggerInterface::class, function() use ($logger) {
            return $logger;
        });

        #
        # Run
        #
        $adapter = app()->make(GithubAdapter::class);
        $issue = new Issue(
            'laravel',
            'framework',
            30192,
            new EnglishText('a'),
            new EnglishText('a')
        );
        # 2019-10-05T18:55:27Z
        $comments = $adapter->fetchComments($issue, Carbon::parse('2019-10-05T18:57:27Z'));

        #
        # Assertion
        #
        $comment = $comments[0];
        $this->assertNotEmpty($comment->body());
        $this->assertNotEmpty($comment->created_at());
    }
}
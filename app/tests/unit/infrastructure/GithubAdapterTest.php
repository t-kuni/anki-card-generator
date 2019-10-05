<?php

namespace tests\unit\infrastructure;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
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
        $issues = $adapter->fetchIssues('laravel', 'framework');
        $comments = $adapter->fetchComments($issues[2], null);

        #
        # Assertion
        #
        $comment = $comments[0];
        $this->assertNotEmpty($comment->body());
        $this->assertNotEmpty($comment->created_at());
    }
}
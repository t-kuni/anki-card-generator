<?php

namespace tests\unit\infrastructure;

use PHPUnit\Framework\TestCase;
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
        var_dump($issues);
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function fetchComments() {
        $adapter = new GithubAdapter();
        $issues = $adapter->fetchIssues('laravel', 'framework');
        $comments = $adapter->fetchComments($issues[0]);
        var_dump($comments);
        $this->assertTrue(true);
    }
}
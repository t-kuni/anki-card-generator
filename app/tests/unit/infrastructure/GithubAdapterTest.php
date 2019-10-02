<?php

namespace tests\unit\infrastructure;

use PHPUnit\Framework\TestCase;
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
}
<?php

namespace tests\unit\infrastructure;

use PHPUnit\Framework\TestCase;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;

class GithubAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function test() {

        $adapter = new GithubAdapter();
        $adapter->test();
        $this->assertTrue(true);
    }
}
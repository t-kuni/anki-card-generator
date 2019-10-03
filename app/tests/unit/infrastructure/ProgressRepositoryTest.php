<?php

namespace tests\unit\infrastructure;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\ProgressRepository;

class ProgressRepositoryTest extends TestCase
{
    /**
     * @test
     */
    public function save() {
        $repo = new ProgressRepository();
        $repo->save('laravel', 'framework', 2, Carbon::create(2000, 1, 1, 0, 0, 0));
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function findByIssue_() {
        $repo = new ProgressRepository();
        $a = $repo->findByIssue('laravel', 'framework', 2);
        var_dump($a);
        $this->assertTrue(true);
    }
}
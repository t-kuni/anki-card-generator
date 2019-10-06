<?php

namespace tests\unit\infrastructure;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;
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

    /**
     * @test
     */
    public function findByRepository_() {
        $logger = \Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('info');
        app()->bind(LoggerInterface::class, function() use ($logger) {
            return $logger;
        });

        $repo = app()->make(IProgressRepository::class);
        $a = $repo->findByRepository('laravel', 'framework');
        var_dump($a);
        $this->assertTrue(true);
    }
}
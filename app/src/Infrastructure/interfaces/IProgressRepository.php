<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

use Carbon\Carbon;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;

interface IProgressRepository
{
    public function save(string $username, string $repository, int $number, Carbon $checkedAt);

    public function findByIssue(string $username, string $repository, int $number);
}
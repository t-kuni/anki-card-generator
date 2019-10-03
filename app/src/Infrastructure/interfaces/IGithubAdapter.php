<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

use Carbon\Carbon;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;

interface IGithubAdapter
{
    public function fetchIssues(string $username, string $repository);

    public function fetchComments(Issue $issue, Carbon $since);
}
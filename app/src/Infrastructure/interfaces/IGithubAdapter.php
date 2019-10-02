<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;

interface IGithubAdapter
{
    public function fetchIssues(string $username, string $repository);

    public function fetchComments(Issue $issue);
}
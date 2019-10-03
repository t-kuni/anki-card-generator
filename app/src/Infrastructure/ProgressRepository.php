<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


use Carbon\Carbon;
use SleekDB\SleekDB;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Progress;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;

class ProgressRepository implements IProgressRepository
{
    public function __construct()
    {
        $this->store = SleekDB::store('progress', '/app/storage');
    }

    public function save(string $username, string $repository, int $number, Carbon $checked_at)
    {
        $ctx = $this->store->keepConditions()
            ->where('username', '=', $username)
            ->where('repository', '=', $repository)
            ->where('number', '=', $number);

        $result = $ctx->fetch();

        if (empty($result[0])) {
            $doc = compact('username', 'repository', 'number', 'checked_at');
            $this->store->insert($doc);
        } else {
            $ctx->update(compact('checked_at'));
        }
    }

    public function findByIssue(string $username, string $repository, int $number) : ?Progress
    {
        $docs = $this->store->where('username', '=', $username)
            ->where('repository', '=', $repository)
            ->where('number', '=', $number)
            ->fetch();

        if (empty($docs[0])) {
            return null;
        } else {
            return new Progress(Carbon::parse($docs[0]['checked_at']));
        }
    }
}
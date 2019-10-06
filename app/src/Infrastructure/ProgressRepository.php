<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


use Carbon\Carbon;
use Psr\Log\LoggerInterface;
use SleekDB\SleekDB;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Progress;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;

class ProgressRepository implements IProgressRepository
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->store = SleekDB::store('progress', '/app/storage');
        $this->logger = $logger;
    }

    public function save(string $username, string $repository, int $number, Carbon $checked_at)
    {
        $this->logger->info('Start to save Progress', func_get_args());

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

        $this->logger->info('End to save Progress');
    }

    public function findByIssue(string $username, string $repository, int $number) : ?Progress
    {
        $this->logger->info('Start to find Progress', func_get_args());

        $docs = $this->store->where('username', '=', $username)
            ->where('repository', '=', $repository)
            ->where('number', '=', $number)
            ->fetch();

        $this->logger->info('End to find Progress');

        if (empty($docs[0])) {
            return null;
        } else {
            return new Progress(Carbon::parse($docs[0]['checked_at']));
        }
    }

    public function findByRepository(string $username, string $repository) : array
    {
        $this->logger->info('Start to find Progress by Repository', func_get_args());

        $docs = $this->store->where('username', '=', $username)
            ->where('repository', '=', $repository)
            ->fetch();

        $this->logger->info('End to find Progress');

        return array_map(function($doc) {
            return new Progress(Carbon::parse($doc['checked_at']));
        }, $docs);
    }
}
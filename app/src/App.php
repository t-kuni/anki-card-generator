<?php

namespace TKuni\AnkiCardGenerator;

use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Domain\AnkiCardAdder;
use TKuni\AnkiCardGenerator\Domain\Models\Card;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\INotificationAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\ITranslateAdapter;

/**
 * Class App
 * メイン処理が記述されたクラス
 *
 * @package TKuni\AnkiCardGenerator
 */
class App
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var IAnkiWebAdapter
     */
    private $ankiWeb;
    /**
     * @var IGithubAdapter
     */
    private $github;
    /**
     * @var ITranslateAdapter
     */
    private $translate;
    /**
     * @var IProgressRepository
     */
    private $progressRepo;
    /**
     * @var INotificationAdapter
     */
    private $notification;

    public function __construct(LoggerInterface $logger, IAnkiWebAdapter $ankiWeb, IGithubAdapter $github,
                                ITranslateAdapter $translate, IProgressRepository $progressRepo,
                                INotificationAdapter $notification)
    {
        $this->logger       = $logger;
        $this->ankiWeb      = $ankiWeb;
        $this->github       = $github;
        $this->translate    = $translate;
        $this->progressRepo = $progressRepo;
        $this->notification = $notification;
    }

    public function run()
    {
        # 開始ログ＆Slack通知
        $this->logger->info("Start application");
        $this->notification->notify("Start application");

        # GithubからIssueの一覧を取得
        $username   = getenv('GITHUB_USER');
        $repository = getenv('GITHUB_REPO');
        $issues     = $this->github->fetchIssues($username, $repository);

        $enTexts = [];

        # 過去に登録済みのIssueかどうかを調べて
        # 未登録ならIssueから英文を切り出す
        $issue = $issues[0]; # 最新のIssueを取り出す
        $since = null;
        if (($progress = $this->progressRepo->findByIssue($issue->username(), $issue->repository(), $issue->number())) !== null) {
            $since = $progress->checked_at()->addMinute(1);
        } else {
            $enTexts = array_merge($enTexts, $issue->title()->separate());
            $enTexts = array_merge($enTexts, $issue->body()->separate());
        }

        # Issueにぶら下がるコメントの一覧を取得し
        # コメントから英文を切り出す
        $comments = $this->github->fetchComments($issue, $since);
        $enTexts  = array_merge(
            $enTexts,
            array_reduce($comments, function ($curr, $comment) {
                return array_merge($curr, $comment->body()->separate());
            }, [])
        );

        # 英文を日本語に翻訳し、Cardオブジェクトを生成する
        $translate = $this->translate;
        $cards = array_map(function ($enText) use ($translate) {
            $jpText = $translate->translate($enText);
            return new Card($enText, $jpText);
        }, $enTexts);

        if (count($cards) === 0) {
            $this->logger->info('Exit application as not found new comments.');
            $this->notification->notify("Exit application as not found new comments.");
            return;
        }

        # カードをAnkiに登録する
        $id   = getenv('ANKI_WEB_ID');
        $pw   = getenv('ANKI_WEB_PW');
        $deck = getenv('ANKI_WEB_DECK');
        $this->ankiWeb->login($id, $pw);
        foreach ($cards as $card) {
            $this->ankiWeb->saveCard($deck, $card);
        }

        # Issueを登録済みとして保存する
        $created_at = null;
        if (!empty($comments[count($comments) - 1]->created_at())) {
            $created_at = $comments[count($comments) - 1]->created_at();
        }
        $this->progressRepo->save($username, $repository, $issue->number(), $created_at);

        # 終了ログ＆Slack通知
        $this->logger->info('Exit application');
        $this->notification->notify("Exit application");
    }
}
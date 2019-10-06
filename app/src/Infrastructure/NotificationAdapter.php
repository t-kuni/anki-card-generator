<?php


namespace TKuni\AnkiCardGenerator\Infrastructure;


use TKuni\AnkiCardGenerator\Infrastructure\interfaces\INotificationAdapter;
use wrapi\slack\slack;

class NotificationAdapter implements INotificationAdapter
{
    /**
     * @var slack
     */
    private $slack;

    public function __construct()
    {
        $this->slack = new slack(getenv('SLACK_API_TOKEN'));
    }

    public function notify($msg)
    {
        $chanel = '#github-issue-2-anki-card';
        $this->slack->chat->postMessage(array(
                "channel" => $chanel,
                "text"    => $msg
            )
        );
    }
}
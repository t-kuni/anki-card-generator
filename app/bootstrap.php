<?php

namespace TKuni\AnkiCardGenerator;

use Dotenv\Dotenv;
use Opis\Container\Container;
use Psr\Log\LoggerInterface;
use SimpleLog\Logger;
use TKuni\AnkiCardGenerator\Infrastructure\AnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\INotificationAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\ITranslateAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\NotificationAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\ProgressRepository;
use TKuni\AnkiCardGenerator\Infrastructure\TranslateAdapter;

require_once __DIR__ . '/vendor/autoload.php';

#
# Load dot env file.
#
Dotenv::create(__DIR__)->safeLoad();

#
# Setup DI Container.
#
$app = new Container();

$app->bind('app', App::class);
$app->singleton(LoggerInterface::class, function() {
    $logger = new Logger('/dev/null', 'default');
    $logger->setOutput(true);
    return $logger;
});
$app->bind(IAnkiWebAdapter::class, AnkiWebAdapter::class);
$app->bind(IGithubAdapter::class, GithubAdapter::class);
$app->bind(ITranslateAdapter::class, TranslateAdapter::class);
$app->bind(IProgressRepository::class, ProgressRepository::class);
$app->bind(INotificationAdapter::class, NotificationAdapter::class);
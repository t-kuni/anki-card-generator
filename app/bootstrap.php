<?php

namespace TKuni\AnkiCardGenerator;

use Dotenv\Dotenv;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Psr\Log\LoggerInterface;
use SimpleLog\Logger;
use TKuni\AnkiCardGenerator\Infrastructure\AnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\ITranslateAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\ProgressRepository;
use TKuni\AnkiCardGenerator\Infrastructure\TranslateAdapter;

require_once __DIR__ . '/vendor/autoload.php';

#
# Load dot env file.
#
Dotenv::create(__DIR__)->load();

#
# Setup DI Container.
#
$app = new Container();
$app->delegate(new ReflectionContainer());

$app->add('app', App::class);
$app->add(LoggerInterface::class, function() {
    return new Logger('/dev/stdout', 'default');
})->setShared(true);
$app->add(IAnkiWebAdapter::class, AnkiWebAdapter::class);
$app->add(IGithubAdapter::class, GithubAdapter::class);
$app->add(ITranslateAdapter::class, TranslateAdapter::class);
$app->add(IProgressRepository::class, ProgressRepository::class);

$app->get('app');
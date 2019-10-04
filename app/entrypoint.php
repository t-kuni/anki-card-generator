<?php

namespace App;

use Dotenv\Dotenv;
use League\Container\Container;
use Psr\Log\LoggerInterface;
use SimpleLog\Logger;
use TKuni\AnkiCardGenerator\App;
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
$dotenv = Dotenv::create(__DIR__);
$dotenv->load();

#
# Setup DI Container.
#
$c = new Container();

$c->add('app', App::class);
$c->add(LoggerInterface::class, function() {
    return new Logger('/dev/stdout', 'default');
})->setShared(true);

$c->add(IAnkiWebAdapter::class, AnkiWebAdapter::class);
$c->add(IGithubAdapter::class, GithubAdapter::class);
$c->add(ITranslateAdapter::class, TranslateAdapter::class);
$c->add(IProgressRepository::class, ProgressRepository::class);

#
# Define Helper Function
#
//if (! function_exists('logger')) {
//    function logger() : LoggerInterface
//    {
//        global $c;
//        return $c->get('logger');
//    }
//}

#
# Boot Application
#
$c->get('app')->run();
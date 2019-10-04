<?php

namespace tests\unit;

use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\App;
use TKuni\AnkiCardGenerator\Domain\Models\Card;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Comment;
use TKuni\AnkiCardGenerator\Domain\Models\Github\Issue;
use TKuni\AnkiCardGenerator\Domain\ObjectValues\EnglishText;
use TKuni\AnkiCardGenerator\Infrastructure\GithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IAnkiWebAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IGithubAdapter;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\IProgressRepository;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\ITranslateAdapter;

class AppTest extends TestCase
{
    /**
     * @test
     */
    public function run_()
    {
        #
        # Prepare
        #
        $logger       = $this->makeLoggerMock();
        $github       = $this->makeGithubMock();
        $translate    = $this->makeTranslateMock();
        $ankiWeb      = $this->makeAnkiWebMock();
        $progressRepo = $this->makeProgressRepoMock();
        app()->get('app');

        #
        # Run
        #
        $app = new App($logger, $ankiWeb, $github, $translate, $progressRepo);
        $app->run();

        #
        # Assertion
        #
        $this->assertTrue(true);
    }

    private function makeGithubMock()
    {
        return new class implements IGithubAdapter
        {
            public function fetchIssues(string $username, string $repository)
            {
                return [
                    new Issue(
                        $username,
                        $repository,
                        1,
                        new EnglishText('title. apple. orange.'),
                        new EnglishText('body. grape.'),
                    ),
                ];
            }

            public function fetchComments(Issue $issue, ?Carbon $since)
            {
                return [
                    new Comment(new EnglishText('body. tomato'))
                ];
            }
        };
    }

    private function makeTranslateMock()
    {
        return new class implements ITranslateAdapter
        {
            public function translate(string $text): string
            {
                switch ($text) {
                    case 'title':
                        return 'タイトル';
                    case 'apple':
                        return 'りんご';
                    case 'orange':
                        return 'オレンジ';
                    case 'body':
                        return '本文';
                    case 'grape':
                        return 'ブドウ';
                    case 'tomato':
                        return 'トマト';
                    default:
                        throw new \Exception('意図しない入力:' . $text);
                        break;
                }
            }
        };
    }

    private function makeAnkiWebMock()
    {
        return new class implements IAnkiWebAdapter
        {
            public function login($id, $pw)
            {
                // TODO: Implement login() method.
            }

            public function saveCard($deck, Card $card)
            {
                if ($card->front() !== 'title' || $card->back() !== 'タイトル') {
                    throw new \Exception('意図しないCard');
                }
                yield;

                if ($card->front() !== 'title' || $card->back() !== 'タイトル') {
                    throw new \Exception('意図しないCard');
                }
                yield;
            }
        };
    }

    private function makeProgressRepoMock()
    {
        return new class implements IProgressRepository
        {
            public function save(string $username, string $repository, int $number, Carbon $checkedAt)
            {
                // TODO: Implement save() method.
            }

            public function findByIssue(string $username, string $repository, int $number)
            {
                // TODO: Implement findByIssue() method.
                return null;
            }
        };
    }

    private function makeLoggerMock()
    {
        return new class implements LoggerInterface
        {

            /**
             * System is unusable.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function emergency($message, array $context = array())
            {
                // TODO: Implement emergency() method.
            }

            /**
             * Action must be taken immediately.
             *
             * Example: Entire website down, database unavailable, etc. This should
             * trigger the SMS alerts and wake you up.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function alert($message, array $context = array())
            {
                // TODO: Implement alert() method.
            }

            /**
             * Critical conditions.
             *
             * Example: Application component unavailable, unexpected exception.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function critical($message, array $context = array())
            {
                // TODO: Implement critical() method.
            }

            /**
             * Runtime errors that do not require immediate action but should typically
             * be logged and monitored.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function error($message, array $context = array())
            {
                // TODO: Implement error() method.
            }

            /**
             * Exceptional occurrences that are not errors.
             *
             * Example: Use of deprecated APIs, poor use of an API, undesirable things
             * that are not necessarily wrong.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function warning($message, array $context = array())
            {
                // TODO: Implement warning() method.
            }

            /**
             * Normal but significant events.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function notice($message, array $context = array())
            {
                // TODO: Implement notice() method.
            }

            /**
             * Interesting events.
             *
             * Example: User logs in, SQL logs.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function info($message, array $context = array())
            {
                // TODO: Implement info() method.
            }

            /**
             * Detailed debug information.
             *
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function debug($message, array $context = array())
            {
                // TODO: Implement debug() method.
            }

            /**
             * Logs with an arbitrary level.
             *
             * @param mixed $level
             * @param string $message
             * @param array $context
             *
             * @return void
             */
            public function log($level, $message, array $context = array())
            {
                // TODO: Implement log() method.
            }
        };
    }
}
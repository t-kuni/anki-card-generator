<?php

namespace tests\unit\infrastructure;

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use TKuni\AnkiCardGenerator\Infrastructure\interfaces\ITranslateAdapter;

class TranslateAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function translate_() {
        $logger = \Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('info');
        app()->bind(LoggerInterface::class, function() use ($logger) {
            return $logger;
        });

        $enText = 'So this PR should be closed or just on hold until 7';
        $actual = app()->make(ITranslateAdapter::class)->translate($enText);
        $expect = 'そのため、このPRは7日までクローズするか保留する必要があります。';
        $this->assertEquals($expect, $actual);
    }
}
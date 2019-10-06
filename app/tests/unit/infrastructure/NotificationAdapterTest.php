<?php

namespace tests\unit\infrastructure;

use PHPUnit\Framework\TestCase;
use TKuni\AnkiCardGenerator\Infrastructure\NotificationAdapter;

class NotificationAdapterTest extends TestCase
{
    /**
     * @test
     */
    public function notify_() {
        #
        # Prepare
        #

        #
        # Run
        #
        $adapter = app()->make(NotificationAdapter::class);
        $adapter->notify('test');

        #
        # Assertion
        #
        $this->assertTrue(true);
    }
}
<?php

namespace TKuni\AnkiCardGenerator\Infrastructure\interfaces;

interface INotificationAdapter
{
    public function notify($msg);
}
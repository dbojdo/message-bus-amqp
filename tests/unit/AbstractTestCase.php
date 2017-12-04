<?php

namespace Webit\MessageBus\Infrastructure\Amqp;

use PHPUnit\Framework\TestCase;
use Webit\MessageBus\Message;

abstract class AbstractTestCase extends TestCase
{
    /**
     * @return Message
     */
    protected function randomMessage()
    {
        return new Message($this->randomString(), $this->randomString());
    }

    /**
     * @return string
     */
    protected function randomString()
    {
        return md5(mt_rand(0, 1000000).microtime());
    }
}
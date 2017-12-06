<?php

namespace Webit\MessageBus\Infrastructure\Amqp;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
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

    /**
     * @return \Prophecy\Prophecy\ObjectProphecy|AMQPChannel
     */
    protected function createChannel()
    {
        return $this->prophesize(AMQPChannel::class);
    }

    /**
     * @return ObjectProphecy|AMQPMessage
     */
    protected function randomAmqpMessage()
    {
        return $this->prophesize(AMQPMessage::class);
    }
}
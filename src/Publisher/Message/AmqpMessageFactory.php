<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Message;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Message;

interface AmqpMessageFactory
{
    /**
     * @param Message $message
     * @return AMQPMessage
     */
    public function create(Message $message);
}
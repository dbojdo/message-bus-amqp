<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Message;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Message;

interface MessageFactory
{
    /**
     * @param AMQPMessage $message
     * @return Message
     */
    public function create(AMQPMessage $message): Message;
}
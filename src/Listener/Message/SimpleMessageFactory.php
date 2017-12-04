<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Message;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Message;

class SimpleMessageFactory implements MessageFactory
{
    /**
     * @inheritdoc
     */
    public function create(AMQPMessage $message): Message
    {
        return new Message($message->get('type'), $message->body);
    }
}
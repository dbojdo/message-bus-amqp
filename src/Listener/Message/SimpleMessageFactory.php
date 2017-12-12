<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Message;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Message;

final class SimpleMessageFactory implements MessageFactory
{
    /**
     * @inheritdoc
     */
    public function create(AMQPMessage $message): Message
    {
        return new Message((string)$message->get('type'), (string)$message->getBody());
    }
}
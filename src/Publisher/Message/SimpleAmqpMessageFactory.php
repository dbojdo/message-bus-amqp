<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Message;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Message;

class SimpleAmqpMessageFactory implements AmqpMessageFactory
{
    /**
     * @inheritdoc
     */
    public function create(Message $message)
    {
        return new AMQPMessage(
            $message->content(),
            [
                'type' => $message->type(),
                'timestamp' => time()
            ]
        );
    }
}

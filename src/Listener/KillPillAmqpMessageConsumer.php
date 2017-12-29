<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\KillPillReceivedException;

final class KillPillAmqpMessageConsumer implements AmqpMessageConsumer
{
    /** @var AmqpMessageConsumer */
    private $innerConsumer;

    public function __construct(AmqpMessageConsumer $innerConsumer)
    {
        $this->innerConsumer = $innerConsumer;
    }

    /**
     * @inheritdoc
     */
    public function consume(AMQPMessage $message)
    {
        if (KillPill::isKillPill($message)) {
            throw KillPillReceivedException::create($message);
        }
        $this->innerConsumer->consume($message);
    }
}

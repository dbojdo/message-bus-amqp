<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\ConnectionAwareChannelFactory;

final class SimpleAmqpListener implements AmqpListener
{
    /** @var ConnectionAwareChannelFactory */
    private $channelFactory;

    /** @var AmqpMessageConsumer */
    private $consumer;

    /** @var string */
    private $queueName;

    /** @var int */
    private $prefetchCount;

    public function __construct(
        ConnectionAwareChannelFactory $channelFactory,
        AmqpMessageConsumer $consumer,
        string $queueName,
        int $prefetchCount = null
    ) {
        $this->channelFactory = $channelFactory;
        $this->consumer = $consumer;
        $this->queueName = $queueName;
        $this->prefetchCount = $prefetchCount;
    }

    /**
     * @inheritdoc
     */
    public function listen(
        int $timeout = 0,
        $noLocal = false,
        $noAck = false,
        $exclusive = false,
        $noWait = false,
        int $ticket = null,
        array $arguments = []
    ) {
        $channel = $this->channelFactory->create();
        if ($this->prefetchCount) {
            $channel->basic_qos(null, $this->prefetchCount, null);
        }

        $channel->basic_consume(
            $this->queueName,
            '',
            $noLocal,
            $noAck,
            $exclusive,
            $noWait,
            array($this->consumer, 'consume'),
            $ticket,
            $arguments
        );

        while($channel->callbacks) {
            $channel->wait(null, null, $timeout);
        }
    }
}

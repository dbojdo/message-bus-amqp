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

    /** @var string */
    private $consumerTag;

    /**
     * AbstractBasicPublicationTarget constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     * @param AmqpMessageConsumer $consumer
     * @param string $queueName
     * @param string $consumerTag
     */
    public function __construct(
        ConnectionAwareChannelFactory $channelFactory,
        AmqpMessageConsumer $consumer,
        string $queueName,
        string $consumerTag = ''
    ) {
        $this->channelFactory = $channelFactory;
        $this->consumer = $consumer;
        $this->queueName = $queueName;
        $this->consumerTag = $consumerTag;
    }

    /**
     * @inheritdoc
     */
    public function listen(
        $noLocal = false,
        $noAck = false,
        $exclusive = false,
        $noWait = false,
        int $ticket = null,
        array $arguments = []
    ) {
        $channel = $this->channelFactory->create();
        $channel->basic_consume(
            $this->queueName,
            $this->consumerTag,
            $noLocal,
            $noAck,
            $exclusive,
            $noWait,
            array($this->consumer, 'consume'),
            $ticket,
            $arguments
        );
    }
}

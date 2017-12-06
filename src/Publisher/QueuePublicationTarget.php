<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\ConnectionAwareChannelFactory;

final class QueuePublicationTarget extends AbstractBasicPublicationTarget
{
    /** @var string */
    private $queueName;

    /**
     * QueuePublicationTarget constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     * @param string $queueName
     */
    public function __construct(ConnectionAwareChannelFactory $channelFactory, string $queueName)
    {
        parent::__construct($channelFactory);
        $this->queueName = $queueName;
    }

    /**
     * @inheritdoc
     */
    protected function exchangeName()
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    protected function routingKey(AMQPMessage $message)
    {
        return $this->queueName;
    }
}

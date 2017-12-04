<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Channel\ConnectionAwareChannelFactory;

class QueuePublicationTarget extends AbstractBasicPublicationTarget
{
    /** @var string */
    private $queueName;

    /**
     * QueuePublicationTarget constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     * @param string $queueName
     * @param bool $mandatory
     * @param bool $immediate
     * @param int $ticket
     */
    public function __construct(
        ConnectionAwareChannelFactory $channelFactory,
        string $queueName,
        bool $mandatory = false,
        bool $immediate = false,
        int $ticket = null
    ) {
        parent::__construct($channelFactory, $mandatory, $immediate, $ticket);
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
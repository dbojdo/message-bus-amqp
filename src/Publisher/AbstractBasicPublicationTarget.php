<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Channel\ConnectionAwareChannelFactory;

abstract class AbstractBasicPublicationTarget implements PublicationTarget
{
    /** @var ConnectionAwareChannelFactory */
    private $channelFactory;

    /** @var bool */
    private $mandatory;

    /** @var bool */
    private $immediate;

    /** @var int */
    private $ticket;

    /**
     * AbstractBasicPublicationTarget constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     * @param bool $mandatory
     * @param bool $immediate
     * @param int $ticket
     */
    public function __construct(
        ConnectionAwareChannelFactory $channelFactory,
        bool $mandatory = false,
        bool $immediate = false,
        int $ticket = null
    ) {
        $this->channelFactory = $channelFactory;
        $this->mandatory = $mandatory;
        $this->immediate = $immediate;
        $this->ticket = $ticket;
    }

    /**
     * @inheritdoc
     */
    public function publish(AMQPMessage $message)
    {
        $channel = $this->channelFactory->create();
        $channel->basic_publish(
            $message,
            $this->exchangeName(),
            $this->routingKey($message),
            $this->mandatory,
            $this->immediate,
            $this->ticket
        );
    }

    /**
     * @return string
     */
    abstract protected function exchangeName();

    /**
     * @param AMQPMessage $message
     * @return string
     */
    abstract protected function routingKey(AMQPMessage $message);
}
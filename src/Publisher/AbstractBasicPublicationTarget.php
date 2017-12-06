<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\ConnectionAwareChannelFactory;

abstract class AbstractBasicPublicationTarget implements PublicationTarget
{
    /** @var ConnectionAwareChannelFactory */
    private $channelFactory;

    /**
     * AbstractBasicPublicationTarget constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     */
    public function __construct(ConnectionAwareChannelFactory $channelFactory)
    {
        $this->channelFactory = $channelFactory;
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
            $this->routingKey($message)
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

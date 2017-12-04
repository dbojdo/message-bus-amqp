<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Channel\ConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\RoutingKey\RoutingKeyResolver;

class ExchangePublicationTarget extends AbstractBasicPublicationTarget
{
    /** @var RoutingKeyResolver */
    private $routingKeyResolver;

    /** @var string */
    private $exchangeName;

    public function __construct(
        ConnectionAwareChannelFactory $channelFactory,
        RoutingKeyResolver $routingKeyResolver,
        $exchangeName,
        bool $mandatory = false,
        bool $immediate = false,
        int $ticket = null
    ) {
        parent::__construct($channelFactory, $mandatory, $immediate, $ticket);
        $this->routingKeyResolver = $routingKeyResolver;
        $this->exchangeName = $exchangeName;
    }

    /**
     * @inheritdoc
     */
    protected function exchangeName()
    {
        return $this->exchangeName;
    }

    /**
     * @inheritdoc
     */
    protected function routingKey(AMQPMessage $message)
    {
        return $this->routingKeyResolver->resolve($message);
    }
}

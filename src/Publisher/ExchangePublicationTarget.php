<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\ConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing\RoutingKeyResolver;

final class ExchangePublicationTarget extends AbstractBasicPublicationTarget
{
    /** @var RoutingKeyResolver */
    private $routingKeyResolver;

    /** @var string */
    private $exchangeName;

    public function __construct(
        ConnectionAwareChannelFactory $channelFactory,
        RoutingKeyResolver $routingKeyResolver,
        $exchangeName
    ) {
        parent::__construct($channelFactory);
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

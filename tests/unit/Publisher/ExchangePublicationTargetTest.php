<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\ConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing\RoutingKeyResolver;

class ExchangePublicationTargetTest extends AbstractTestCase
{
    /** @var ConnectionAwareChannelFactory|ObjectProphecy */
    private $channelFactory;

    /** @var RoutingKeyResolver|ObjectProphecy */
    private $routingKeyResolver;

    protected function setUp()
    {
        $this->channelFactory = $this->prophesize(ConnectionAwareChannelFactory::class);
        $this->routingKeyResolver = $this->prophesize(RoutingKeyResolver::class);
    }

    /**
     * @test
     */
    public function itPublishesToQueue()
    {
        $target = new ExchangePublicationTarget(
            $this->channelFactory->reveal(),
            $this->routingKeyResolver->reveal(),
            $exchangeName = $this->randomString()
        );

        $channel = $this->createChannel();
        $this->channelFactory->create()->willReturn($channel->reveal());

        $message = $this->randomAmqpMessage()->reveal();

        $this->routingKeyResolver->resolve($message)->willReturn($routingKey = $this->randomString());

        $channel->basic_publish($message, $exchangeName, $routingKey)->shouldBeCalled();

        $target->publish($message);
    }
}

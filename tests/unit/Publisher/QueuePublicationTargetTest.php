<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\ConnectionAwareChannelFactory;

class QueuePublicationTargetTest extends AbstractTestCase
{
    /** @var ConnectionAwareChannelFactory|ObjectProphecy */
    private $channelFactory;

    protected function setUp()
    {
        $this->channelFactory = $this->prophesize(ConnectionAwareChannelFactory::class);
    }

    /**
     * @test
     */
    public function shouldPublishToQueue()
    {
        $target = new QueuePublicationTarget(
            $this->channelFactory->reveal(),
            $queueName = $this->randomString()
        );

        $channel = $this->createChannel();
        $this->channelFactory->create()->willReturn($channel->reveal());

        $message = $this->randomAmqpMessage()->reveal();
        $channel->basic_publish($message, '', $queueName)->shouldBeCalled();

        $target->publish($message);
    }
}

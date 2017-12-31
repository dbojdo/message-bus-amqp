<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Consumer;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\CouldNotConsumeAmqpMessageException;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Message\MessageFactory;

class MessageConsumingAmqpMessageConsumerTest extends AbstractTestCase
{
    /** @var MessageFactory|ObjectProphecy */
    private $messageFactory;

    /** @var Consumer|ObjectProphecy */
    private $consumer;

    /** @var MessageConsumingAmqpMessageConsumer */
    private $listener;

    protected function setUp()
    {
        $this->messageFactory = $this->prophesize(MessageFactory::class);
        $this->consumer = $this->prophesize(Consumer::class);
        $this->listener = new MessageConsumingAmqpMessageConsumer(
            $this->consumer->reveal(),
            $this->messageFactory->reveal()
        );
    }

    /**
     * @test
     */
    public function itConsumesAmqpMessage()
    {
        $amqpMessage = $this->randomAmqpMessage()->reveal();
        $this->messageFactory->create($amqpMessage)->willReturn($message = $this->randomMessage());
        $this->consumer->consume($message)->shouldBeCalled();

        $this->listener->consume($amqpMessage);
    }

    /**
     * @test
     */
    public function itWrapsConsumptionException()
    {
        $amqpMessage = $this->randomAmqpMessage()->reveal();
        $this->messageFactory->create($amqpMessage)->willReturn($message = $this->randomMessage());
        $this->consumer->consume($message)
            ->willThrow($exception = Consumer\Exception\CannotConsumeMessageException::forMessage($message));


        try {
            $this->listener->consume($amqpMessage);
        } catch (AmqpMessageConsumptionException $e) {
            $this->assertEquals(CouldNotConsumeAmqpMessageException::forMessage($amqpMessage, null, 0, $exception), $e);
        }
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;

class FeedbackSendingAmqpMessageConsumerTest extends AbstractTestCase
{
    /** @var AmqpMessageConsumer|ObjectProphecy */
    private $innerListener;

    /** @var AmqpMessageFeedbackSender|ObjectProphecy */
    private $feedbackSender;

    /** @var FeedbackSendingAmqpMessageConsumer */
    private $listener;

    protected function setUp()
    {
        $this->innerListener = $this->prophesize(AmqpMessageConsumer::class);
        $this->feedbackSender = $this->prophesize(AmqpMessageFeedbackSender::class);

        $this->listener = new FeedbackSendingAmqpMessageConsumer(
            $this->innerListener->reveal(),
            $this->feedbackSender->reveal()
        );
    }

    /**
     * @test
     */
    public function itAcknowledgesMessage()
    {
        $amqpMessage = $this->randomAmqpMessage()->reveal();
        $this->innerListener->consume($amqpMessage)->shouldBeCalled();
        $this->feedbackSender->acknowledge($amqpMessage)->shouldBeCalled();
        $this->feedbackSender->nacknowledge($amqpMessage)->shouldNotBeCalled();

        $this->listener->consume($amqpMessage);
    }

    /**
     * @test
     * @expectedException \Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException
     */
    public function itNAcknowledgesMessageOnException()
    {
        $amqpMessage = $this->randomAmqpMessage()->reveal();
        $this->innerListener->consume($amqpMessage)
            ->willThrow($this->prophesize(AmqpMessageConsumptionException::class)->reveal());
        $this->feedbackSender->nacknowledge($amqpMessage)->shouldBeCalled();
        $this->feedbackSender->acknowledge($amqpMessage)->shouldNotBeCalled();

        $this->listener->consume($amqpMessage);
    }
}

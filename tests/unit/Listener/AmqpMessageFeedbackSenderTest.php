<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class AmqpMessageFeedbackSenderTest extends AbstractTestCase
{
    /** @var AmqpMessageFeedbackSender */
    private $feedbackSender;

    protected function setUp()
    {
        $this->feedbackSender = new AmqpMessageFeedbackSender();
    }

    /**
     * @test
     */
    public function itAcknowledgesMessage()
    {
        /**
         * @var AMQPMessage $message
         * @var AMQPChannel $channel
         * @var string $deliveryTag
         */
        list($message, $channel, $deliveryTag) = $this->messageToBeAcknowledged();
        $channel->basic_ack($deliveryTag)->shouldBeCalled();

        $this->feedbackSender->acknowledge($message);
    }

    /**
     * @test
     */
    public function itNacknowledgesMessage()
    {
        /**
         * @var AMQPMessage $message
         * @var AMQPChannel $channel
         * @var string $deliveryTag
         */
        list($message, $channel, $deliveryTag) = $this->messageToBeAcknowledged();
        $channel->basic_nack($deliveryTag, false, $requeue = true)->shouldBeCalled();

        $this->feedbackSender->nacknowledge($message, $requeue);
    }

    /**
     * @test
     */
    public function itRejectsMessage()
    {
        /**
         * @var AMQPMessage $message
         * @var AMQPChannel $channel
         * @var string $deliveryTag
         */
        list($message, $channel, $deliveryTag) = $this->messageToBeAcknowledged();
        $channel->basic_reject($deliveryTag, $requeue = true)->shouldBeCalled();

        $this->feedbackSender->reject($message, $requeue);
    }

    /**
     * @return array
     */
    private function messageToBeAcknowledged()
    {
        $message = $this->randomAmqpMessage();
        $channel = $this->createChannel();
        $message->get('channel')->willReturn($channel->reveal());
        $message->get('delivery_tag')->willReturn($deliveryTag = $this->randomString());

        return [$message->reveal(), $channel, $deliveryTag];
    }
}

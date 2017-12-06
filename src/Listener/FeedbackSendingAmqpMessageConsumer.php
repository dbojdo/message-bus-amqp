<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;

final class FeedbackSendingAmqpMessageConsumer implements AmqpMessageConsumer
{
    /** @var AmqpMessageConsumer */
    private $innerListener;

    /** @var AmqpMessageFeedbackSender */
    private $feedbackSender;

    /**
     * FeedbackSendingAmqpListener constructor.
     * @param AmqpMessageConsumer $innerListener
     * @param AmqpMessageFeedbackSender $feedbackSender
     */
    public function __construct(AmqpMessageConsumer $innerListener, AmqpMessageFeedbackSender $feedbackSender = null)
    {
        $this->innerListener = $innerListener;
        $this->feedbackSender = $feedbackSender ?: new AmqpMessageFeedbackSender();
    }

    /**
     * @inheritdoc
     */
    public function consume(AMQPMessage $message)
    {
        try {
            $this->innerListener->consume($message);
        } catch (AmqpMessageConsumptionException $e) {
            $this->feedbackSender->nacknowledge($message);
            throw $e;
        }
        $this->feedbackSender->acknowledge($message);
    }
}

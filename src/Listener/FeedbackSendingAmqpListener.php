<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Consumer\Exception\AmqpMessageConsumptionException;

class FeedbackSendingAmqpListener implements AmqpListener
{
    /** @var AmqpListener */
    private $innerListener;

    /** @var AmqpMessageFeedbackSender */
    private $feedbackSender;

    /**
     * FeedbackSendingAmqpListener constructor.
     * @param AmqpListener $innerListener
     * @param AmqpMessageFeedbackSender $feedbackSender
     */
    public function __construct(AmqpListener $innerListener, AmqpMessageFeedbackSender $feedbackSender)
    {
        $this->innerListener = $innerListener;
        $this->feedbackSender = $feedbackSender;
    }

    /**
     * @inheritdoc
     */
    public function onMessage(AMQPMessage $message)
    {
        try {
            $this->innerListener->onMessage($message);
        } catch (AmqpMessageConsumptionException $e) {
            $this->feedbackSender->nacknowledge($message);
            throw $e;
        }
        $this->feedbackSender->acknowledge($message);
    }
}

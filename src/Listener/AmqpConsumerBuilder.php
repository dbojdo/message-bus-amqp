<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use Psr\Log\LoggerInterface;
use Webit\MessageBus\Consumer;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Message\MessageFactory;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Message\SimpleMessageFactory;
use Webit\MessageBus\VoidConsumer;

class AmqpConsumerBuilder
{
    /** @var Consumer */
    private $consumer;

    /** @var MessageFactory */
    private $messageFactory;

    /** @var bool */
    private $shouldSendFeedback = true;

    /** @var ConsumerLogger */
    private $logger;

    /** @var bool */
    private $supportKillPill = true;

    /**
     * @return AmqpConsumerBuilder
     */
    public static function create(): AmqpConsumerBuilder
    {
        return new self();
    }

    /**
     * @param bool $shouldSendFeedback
     */
    public function shouldSendFeedback(bool $shouldSendFeedback)
    {
        $this->shouldSendFeedback = $shouldSendFeedback;
    }

    public function setMessageFactory(MessageFactory $factory)
    {
        $this->messageFactory = $factory;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = new ConsumerLogger($logger);
    }

    public function setConsumer(Consumer $consumer)
    {
        $this->consumer = $consumer;
    }

    public function supportKillPill(bool $supportKillPill)
    {
        $this->supportKillPill = $supportKillPill;
    }

    /**
     * @return AmqpMessageConsumer
     */
    public function build(): AmqpMessageConsumer
    {
        $amqpConsumer = new MessageConsumingAmqpMessageConsumer($this->consumer(), $this->messageFactory());
        if ($this->shouldSendFeedback) {
            $amqpConsumer = new FeedbackSendingAmqpMessageConsumer(
                $amqpConsumer
            );
        }

        if ($this->supportKillPill) {
            $amqpConsumer = new KillPillAmqpMessageConsumer($amqpConsumer);
        }

        if ($this->logger) {
            $amqpConsumer = new LoggingAmqpMessageConsumer($amqpConsumer, $this->logger);
        }

        return $amqpConsumer;
    }

    /**
     * @return MessageFactory
     */
    private function messageFactory(): MessageFactory
    {
        if (!$this->messageFactory) {
            $this->messageFactory = new SimpleMessageFactory();
        }

        return $this->messageFactory;
    }

    /**
     * @return Consumer
     */
    private function consumer(): Consumer
    {
        if (!$this->consumer) {
            $this->consumer = new VoidConsumer();
        }

        return $this->consumer;
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Consumer;
use Webit\MessageBus\Exception\MessageConsumptionException;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Message\MessageFactory;

class MessageConsumingAmqpListener
{
    /** @var Consumer */
    private $consumer;

    /** @var MessageFactory */
    private $messageFactory;

    /**
     * AmqpListener constructor.
     * @param Consumer $consumer
     * @param MessageFactory $messageFactory
     */
    public function __construct(Consumer $consumer, MessageFactory $messageFactory)
    {
        $this->consumer = $consumer;
        $this->messageFactory = $messageFactory;
    }

    /**
     * @param AMQPMessage $message
     */
    public function onMessage(AMQPMessage $message)
    {
        $messageBusMessage = $this->messageFactory->create($message);
        try {
            $this->consumer->consume($messageBusMessage);
        } catch (MessageConsumptionException $exception) {
            throw AmqpMessageConsumptionException::forMessage($message, 0, $exception);
        }
    }
}

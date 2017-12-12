<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;

final class LoggingAmqpMessageConsumer implements AmqpMessageConsumer
{
    /** @var AmqpMessageConsumer */
    private $innerConsumer;

    /** @var ConsumerLogger */
    private $logger;

    /**
     * LoggingAmqpMessageConsumer constructor.
     * @param AmqpMessageConsumer $innerConsumer
     * @param ConsumerLogger $logger
     */
    public function __construct(AmqpMessageConsumer $innerConsumer, ConsumerLogger $logger)
    {
        $this->innerConsumer = $innerConsumer;
        $this->logger = $logger ?: new ConsumerLogger();
    }

    /**
     * @inheritdoc
     */
    public function consume(AMQPMessage $message)
    {
        $this->logger->logPreConsume($message);

        try {
            $this->innerConsumer->consume($message);
        } catch (AmqpMessageConsumptionException $e) {
            $this->logger->logConsumptionException($e);
            throw $e;
        }

        $this->logger->logPostConsume($message);
    }
}

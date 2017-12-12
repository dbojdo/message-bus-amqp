<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;

class ConsumerLogger
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * ConsumerLogger constructor.
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param AMQPMessage $message
     */
    public function logPreConsume(AMQPMessage $message)
    {
        $this->logger->info(
            '[AMQP Message Consumer] Consuming a message.',
            $this->infoContext($message)
        );
    }

    /**
     * @param AMQPMessage $message
     */
    public function logPostConsume(AMQPMessage $message)
    {
        $this->logger->info(
            '[AMQP Message Consumer] Message has been consumed.',
            $this->infoContext($message)
        );
    }

    /**
     * @param AmqpMessageConsumptionException $exception
     */
    public function logConsumptionException(AmqpMessageConsumptionException $exception)
    {
        $this->logger->alert(
            '[AMQP Message Consumer] Error during message consumption.',
            $this->alertContext($exception)
        );
    }

    /**
     * @param AmqpMessageConsumptionException $e
     * @return array
     */
    private function alertContext(AmqpMessageConsumptionException $e): array
    {
        $message = $e->amqpMessage();
        return [
            'messageType' => $this->messageType($message),
            'messageBody' => $message->getBody(),
            'exceptionClass' => get_class($e),
            'exceptionMessage' => $e->getMessage(),
            'exceptionTrace' => $e->getTraceAsString()
        ];
    }

    /**
     * @param AMQPMessage $message
     * @return string
     */
    private function messageType(AMQPMessage $message): string
    {
        try {
            return $message->get('type');
        } catch (\OutOfBoundsException $e) {
            return '';
        }
    }

    /**
     * @param AMQPMessage $message
     * @return array
     */
    private function infoContext(AMQPMessage $message): array
    {
        return [
            'messageType' => $this->messageType($message),
            'messageBody' => $message->getBody()
        ];
    }
}
<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Exception;

use PhpAmqpLib\Message\AMQPMessage;

abstract class AbstractAmqpMessageConsumptionException extends \RuntimeException implements AmqpMessageConsumptionException
{
    /**
     * @var AMQPMessage
     */
    protected $amqpMessage;

    /**
     * @param AMQPMessage $amqpMessage
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     * @return AmqpMessageConsumptionException
     */
    public static function forMessage(AMQPMessage $amqpMessage, string $message = null, $code = 0, \Exception $previous = null)
    {
        $exception = new static(
            $message ?: 'Error during AMQP message consumption.',
            $code,
            $previous
        );

        $exception->amqpMessage = $amqpMessage;

        return $exception;
    }

    /**
     * @return AMQPMessage
     */
    public function amqpMessage(): AMQPMessage
    {
        return $this->amqpMessage;
    }
}

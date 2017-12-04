<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Exception;

use PhpAmqpLib\Message\AMQPMessage;

class AmqpMessageConsumptionException extends \RuntimeException
{
    /**
     * @var AMQPMessage
     */
    private $amqpMessage;

    /**
     * @param AMQPMessage $message
     * @param int $code
     * @param \Exception|null $previous
     * @return AmqpMessageConsumptionException
     */
    public static function forMessage(AMQPMessage $message, $code = 0, \Exception $previous = null)
    {
        $exception = new self(
            sprintf('Error during AMQP message consumption.'),
            $code,
            $previous
        );

        $exception->amqpMessage = $message;

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
<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Exception;

use PhpAmqpLib\Message\AMQPMessage;

class KillPillReceivedException extends AbstractAmqpMessageConsumptionException
{
    /**
     * @param AMQPMessage $message
     * @return AmqpMessageConsumptionException
     */
    public static function create(AMQPMessage $message)
    {
        return self::forMessage($message, 'Kill Pill received!');
    }
}

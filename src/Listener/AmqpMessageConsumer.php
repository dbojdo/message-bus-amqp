<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;

interface AmqpMessageConsumer
{
    /**
     * @param AMQPMessage $message
     * @throws AmqpMessageConsumptionException
     */
    public function consume(AMQPMessage $message);
}
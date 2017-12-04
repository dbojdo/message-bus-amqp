<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Consumer\Exception\AmqpMessageConsumptionException;

interface AmqpListener
{
    /**
     * @param AMQPMessage $message
     * @throws AmqpMessageConsumptionException
     */
    public function onMessage(AMQPMessage $message);
}
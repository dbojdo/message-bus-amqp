<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Exception;

use PhpAmqpLib\Message\AMQPMessage;

interface AmqpMessageConsumptionException extends \Throwable
{
    public function amqpMessage(): AMQPMessage;
}

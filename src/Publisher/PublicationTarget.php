<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;

interface PublicationTarget
{
    /**
     * @param AMQPMessage $message
     */
    public function publish(AMQPMessage $message);
}
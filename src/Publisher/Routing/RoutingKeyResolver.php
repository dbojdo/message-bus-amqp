<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing;

use PhpAmqpLib\Message\AMQPMessage;

interface RoutingKeyResolver
{
    /**
     * @param AMQPMessage $message
     * @return string
     */
    public function resolve(AMQPMessage $message);
}
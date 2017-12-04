<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\RoutingKey;

use PhpAmqpLib\Message\AMQPMessage;

class VoidRoutingKeyResolver implements RoutingKeyResolver
{
    /**
     * @inheritdoc
     */
    public function resolve(AMQPMessage $message)
    {
        return '';
    }
}

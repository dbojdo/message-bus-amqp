<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing;

use PhpAmqpLib\Message\AMQPMessage;

final class VoidRoutingKeyResolver implements RoutingKeyResolver
{
    /**
     * @inheritdoc
     */
    public function resolve(AMQPMessage $message)
    {
        return '';
    }
}

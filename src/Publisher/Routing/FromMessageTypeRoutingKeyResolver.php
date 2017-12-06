<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing;

use PhpAmqpLib\Message\AMQPMessage;

final class FromMessageTypeRoutingKeyResolver implements RoutingKeyResolver
{
    /**
     * @inheritdoc
     */
    public function resolve(AMQPMessage $message)
    {
        try {
            return $message->get('type');
        } catch (\OutOfBoundsException $e) {
            return '';
        }
    }
}
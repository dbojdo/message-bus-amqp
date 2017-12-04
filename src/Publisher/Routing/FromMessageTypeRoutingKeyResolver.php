<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\RoutingKey;

use PhpAmqpLib\Message\AMQPMessage;

class FromMessageTypeRoutingKeyResolver implements RoutingKeyResolver
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
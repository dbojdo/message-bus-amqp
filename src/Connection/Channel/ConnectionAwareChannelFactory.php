<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Channel;

use PhpAmqpLib\Channel\AMQPChannel;

interface ConnectionAwareChannelFactory
{
    /**
     * @return AMQPChannel
     */
    public function create();
}
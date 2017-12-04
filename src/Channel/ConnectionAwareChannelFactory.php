<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Channel;

use PhpAmqpLib\Channel\AMQPChannel;

interface ConnectionAwareChannelFactory
{
    /**
     * @return AMQPChannel
     */
    public function create();
}
<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection;

use PhpAmqpLib\Connection\AMQPStreamConnection;

interface ConnectionFactory
{
    /**
     * @param ConnectionParams $connectionParams
     * @return AMQPStreamConnection
     */
    public function create(ConnectionParams $connectionParams);
}

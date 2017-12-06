<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Exception\NoViableConnectionsInPoolException;

interface ConnectionPool
{
    /**
     * @return AMQPStreamConnection
     * @throws NoViableConnectionsInPoolException
     */
    public function current(): AMQPStreamConnection;

    public function disposeCurrent();
}

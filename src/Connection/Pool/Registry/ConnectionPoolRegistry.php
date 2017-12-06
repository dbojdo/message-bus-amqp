<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Registry;

use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPool;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Registry\Exception\ConnectionPoolNotFoundException;

interface ConnectionPoolRegistry
{
    /**
     * @param string $poolName
     * @return ConnectionPool
     * @throws ConnectionPoolNotFoundException
     */
    public function connectionPool(string $poolName): ConnectionPool;

    /**
     * @param ConnectionPool $connectionPool
     * @param string $poolName
     */
    public function registerConnectionPool(ConnectionPool $connectionPool, string $poolName);
}
<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Exception\NoViableConnectionsInPoolException;

final class BasicConnectionPool implements ConnectionPool
{
    /** @var AMQPStreamConnection[] */
    private $connections;

    /** @var ConnectionPoolLogger */
    private $logger;

    /** @var string */
    private $current;

    /**
     * ConnectionPool constructor.
     * @param AMQPStreamConnection[] $connections
     * @param ConnectionPoolLogger|null $logger
     */
    public function __construct(array $connections, ConnectionPoolLogger $logger = null)
    {
        $this->connections = $connections;
        $this->logger = $logger ?: new ConnectionPoolLogger();
    }

    /**
     * @inheritdoc
     */
    public function current(): AMQPStreamConnection
    {
        if (!$this->current) {
            $this->current = $this->random();
            $this->logger->connectionChosen($this->current);
        }

        return $this->connections[$this->current];
    }

    /**
     * @inheritdoc
     */
    public function disposeCurrent()
    {
        if (!$this->current) {
            return;
        }

        try {
            $this->current()->close();
        } catch (\Exception $e) {
        }

        $this->logger->connectionDisposed($this->current);

        unset($this->connections[$this->current]);

        $this->current = null;
    }

    /**
     * @return mixed
     */
    private function random()
    {
        if ($this->connections) {
            $connectionNames = array_keys($this->connections);
            $connectionName = mt_rand(0, count($this->connections) - 1);

            return $connectionNames[$connectionName];
        }

        throw NoViableConnectionsInPoolException::create();
    }
}

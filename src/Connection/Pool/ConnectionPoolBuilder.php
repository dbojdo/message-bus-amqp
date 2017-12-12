<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool;

use Psr\Log\LoggerInterface;
use Webit\MessageBus\Infrastructure\Amqp\Connection\ConnectionFactory;
use Webit\MessageBus\Infrastructure\Amqp\Connection\ConnectionParams;
use Webit\MessageBus\Infrastructure\Amqp\Connection\LazyConnectionFactory;

class ConnectionPoolBuilder
{
    /** @var ConnectionFactory */
    private $connectionFactory;

    /** @var LoggerInterface */
    private $logger;

    /** @var array */
    private $connections = [];

    /**
     * @return ConnectionPoolBuilder
     */
    public static function create()
    {
        return new self();
    }

    /**
     * @param ConnectionFactory $factory
     */
    public function setConnectionFactory(ConnectionFactory $factory)
    {
        $this->connectionFactory = $factory;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param ConnectionParams $params
     * @param string $connectionName
     * @return $this
     */
    public function registerConnection(
        ConnectionParams $params,
        $connectionName = null
    ) {
        $this->connections[$connectionName ?: (string)$params] = $params;

        return $this;
    }

    /**
     * @return BasicConnectionPool
     */
    public function build()
    {
        $connections = [];
        $connectionFactory = $this->connectionFactory();
        foreach ($this->connections as $connectionName => $connectionParams) {
            $connection = $this->connectionFactory->create($connectionParams);
            $connections[$connectionName] = $connection;
        }

        return new BasicConnectionPool($connections, $this->logger ? new ConnectionPoolLogger($this->logger) : null);
    }

    /**
     * @return ConnectionFactory|LazyConnectionFactory
     */
    private function connectionFactory()
    {
        if ($this->connectionFactory == null) {
            $this->connectionFactory = new LazyConnectionFactory();
        }

        return $this->connectionFactory;
    }
}

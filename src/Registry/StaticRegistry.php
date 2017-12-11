<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Registry;

use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPool;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Registry\ConnectionPoolRegistry;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Registry\Exception\ConnectionPoolNotFoundException;
use Webit\MessageBus\Infrastructure\Amqp\Listener\AmqpListener;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Registry\Exception\ListeningNotFoundException;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Registry\ListenerRegistry;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\AmqpPublisher;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Registry\Exception\PublisherNotFoundException;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Registry\PublisherRegistry;

final class StaticRegistry implements ListenerRegistry, PublisherRegistry, ConnectionPoolRegistry
{
    /** @var ConnectionPool[] */
    private $connectionPools;

    /** @var AmqpListener[] */
    private $listeners;

    /** @var AmqpPublisher[] */
    private $publishers;

    /**
     * StaticRegistry constructor.
     * @param ConnectionPool[] $connectionPools
     * @param AmqpListener[] $listeners
     * @param AmqpPublisher[] $publishers
     */
    public function __construct(array $connectionPools = [], array $publishers = [], array $listeners = [])
    {
        $this->connectionPools = $connectionPools;
        $this->listeners = $listeners;
        $this->publishers = $publishers;
    }

    /**
     * @inheritdoc
     */
    public function connectionPool(string $poolName): ConnectionPool
    {
        if (!$this->connectionPools[$poolName]) {
            throw ConnectionPoolNotFoundException::fromPoolName($poolName);
        }

        return $this->connectionPools[$poolName];
    }

    /**
     * @inheritdoc
     */
    public function registerConnectionPool(ConnectionPool $connectionPool, string $poolName)
    {
        $this->connectionPools[$poolName] = $connectionPool;
    }

    /**
     * @inheritdoc
     */
    public function publisher(string $publisherName): AmqpPublisher
    {
        if (!$this->publishers[$publisherName]) {
            throw PublisherNotFoundException::fromPublisherName($publisherName);
        }

        return $this->publishers[$publisherName];
    }

    /**
     * @inheritdoc
     */
    public function registerPublisher(AmqpPublisher $publisher, string $publisherName)
    {
        $this->publishers[$publisherName] = $publisher;
    }

    /**
     * @inheritdoc
     */
    public function listener(string $listenerName): AmqpListener
    {
        if (!$this->listeners[$listenerName]) {
            throw ListeningNotFoundException::fromListenerName($listenerName);
        }

        return $this->listeners[$listenerName];
    }

    /**
     * @inheritdoc
     */
    public function registerListener(AmqpListener $listener, string $listenerName)
    {
        $this->listeners[$listenerName] = $listener;
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Integration;

use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\CachingChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\NewChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Connection\ConnectionParams;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPool;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPoolBuilder;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Exception\NoViableConnectionsInPoolException;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exchange;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeManager;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeType;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\Queue;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\QueueManager;
use Webit\MessageBus\Message;

abstract class AbstractIntegrationTestCase extends TestCase
{
    /** @var ConnectionPool[] */
    private $connectionPools = [];

    /** @var Exchange[] */
    private $exchanges = [];

    /** @var Queue[] */
    private $queues = [];

    /**
     * @param bool $newConnection
     * @return ConnectionPool
     */
    protected function connectionPool($newConnection = false)
    {
        if ($newConnection || !$this->connectionPools) {
            $connectionPoolBuilder = ConnectionPoolBuilder::create();
            $connectionPoolBuilder->registerConnection($this->connectionParams());
            $this->connectionPools[] = $connectionPoolBuilder->build();
        }

        return $this->connectionPools[count($this->connectionPools) - 1];
    }

    /**
     * @return ConnectionParams
     */
    protected function connectionParams(): ConnectionParams
    {
        $host = getenv('rabbitmq.host');
        $user = getenv('rabbitmq.user');
        $password = getenv('rabbitmq.password');
        $port = getenv('rabbitmq.port') ?: '5672';
        $vhost = getenv('rabbitmq.vhost') ?: '/';

        return new ConnectionParams(
            $host,
            $port,
            $user,
            $password,
            $vhost
        );
    }


    /**
     * @return ExchangeManager
     */
    protected function exchangeManager()
    {
        return new ExchangeManager(
            new CachingChannelConnectionAwareChannelFactory(
                new NewChannelConnectionAwareChannelFactory(
                    $this->connectionPool()
                )
            )
        );
    }

    /**
     * @return QueueManager
     */
    protected function queueManager()
    {
        return new QueueManager(
            new CachingChannelConnectionAwareChannelFactory(
                new NewChannelConnectionAwareChannelFactory(
                    $this->connectionPool()
                )
            )
        );
    }

    /**
     * @param string|null $name
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param bool $autoDelete
     * @return Queue
     */
    protected function queue(
        string $name = null,
        bool $passive = false,
        bool $durable = false,
        bool $exclusive = false,
        bool $autoDelete = true
    ) {
        $queue = new Queue(
            $name ?: $this->randomString(),
            $passive,
            $durable,
            $exclusive,
            $autoDelete
        );

        $this->queues[] = $queue;

        return $queue;
    }

    /**
     * @param string|null $name
     * @param ExchangeType|null $type
     * @param bool $passive
     * @param bool $durable
     * @param bool $autoDelete
     * @param bool $internal
     * @return Exchange
     */
    protected function exchange(
        string $name = null,
        ExchangeType $type = null,
        bool $passive = false,
        bool $durable = false,
        bool $autoDelete = true,
        bool $internal = false
    ) {
        $exchange = new Exchange(
            $name ?: $this->randomString(),
            $type ?: ExchangeType::topic(),
            $passive,
            $durable,
            $autoDelete,
            $internal
        );

        $this->exchanges[] = $exchange;

        return $exchange;
    }

    /**
     * @return Message
     */
    protected function randomMessage()
    {
        return new Message($this->randomString(), $this->randomString());
    }

    /**
     * @return string
     */
    protected function randomString()
    {
        return md5(mt_rand(0, 1000000).microtime());
    }

    /**
     * @return AMQPMessage
     */
    protected function randomAmqpMessage()
    {
        return new AMQPMessage($this->randomString());
    }

    protected function tearDown()
    {
        foreach ($this->connectionPools as $connectionPool) {
            $channel = $connectionPool->current()->channel();
            foreach ($this->queues as $queue) {
                try {
                    $channel->queue_delete($queue->name());
                } catch (\Exception $e) {}
            }

            foreach ($this->exchanges as $exchange) {
                try {
                    $channel->exchange_delete($exchange->name());
                } catch (\Exception $e) {}
            }

            try {
                while ($connection = $connectionPool->current()) {
                    $connectionPool->disposeCurrent();
                }
            } catch (NoViableConnectionsInPoolException $e) {}
        }

        $this->queues = [];
        $this->exchanges = [];
        $this->connectionPools = [];
    }
}

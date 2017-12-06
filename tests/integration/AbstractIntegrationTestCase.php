<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Integration;

use PhpAmqpLib\Connection\AMQPLazyConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use Webit\MessageBus\Infrastructure\Amqp\Channel\NewChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exchange;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeManager;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeType;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\Queue;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\QueueManager;
use Webit\MessageBus\Message;

abstract class AbstractIntegrationTestCase extends TestCase
{
    /** @var AMQPLazyConnection[] */
    private $rabbitMqConnections;

    /** @var Exchange[] */
    private $exchanges = [];

    /** @var Queue[] */
    private $queues = [];

    protected function rabbitMqConnection($newConnection = false)
    {
        $host = getenv('rabbitmq.host');
        $user = getenv('rabbitmq.user');
        $password = getenv('rabbitmq.password');
        $port = getenv('rabbitmq.port') ?: '5671';
        $vhost = getenv('rabbitmq.vhost') ?: '/';

        if ($newConnection || !$this->rabbitMqConnections) {
            $this->rabbitMqConnections[] = new AMQPLazyConnection(
                $host,
                $port,
                $user,
                $password,
                $vhost
            );
        }

        return $this->rabbitMqConnections[count($this->rabbitMqConnections) -1];
    }

    /**
     * @return ExchangeManager
     */
    protected function exchangeManager()
    {
        return new ExchangeManager(
            new NewChannelConnectionAwareChannelFactory(
                $this->rabbitMqConnection()
            )
        );
    }

    /**
     * @return QueueManager
     */
    protected function queueManager()
    {
        return new QueueManager(
            new NewChannelConnectionAwareChannelFactory(
                $this->rabbitMqConnection()
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
        $channel = $this->rabbitMqConnection()->channel();
        foreach ($this->queues as $queue) {
            $channel->queue_delete($queue->name());
        }

        foreach ($this->exchanges as $exchange) {
            $channel->exchange_delete($exchange->name());
        }

        foreach ($this->rabbitMqConnections as $connection) {
            try {
                $connection->close();
            } catch (\Exception $e) {}
        }

        $this->rabbitMqConnections = [];
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Channel;

use PhpAmqpLib\Connection\AMQPStreamConnection;

class NewChannelConnectionAwareChannelFactory implements ConnectionAwareChannelFactory
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * NewChannelConnectionAwareChannelFactory constructor.
     * @param AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritdoc
     */
    public function create()
    {
        return $this->connection->channel();
    }
}
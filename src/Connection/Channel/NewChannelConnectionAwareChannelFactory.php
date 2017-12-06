<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Channel;

use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPool;

final class NewChannelConnectionAwareChannelFactory implements ConnectionAwareChannelFactory
{
    /**
     * @var ConnectionPool
     */
    private $connectionPool;

    /**
     * NewChannelConnectionAwareChannelFactory constructor.
     * @param ConnectionPool $connectionPool
     */
    public function __construct(ConnectionPool $connectionPool)
    {
        $this->connectionPool = $connectionPool;
    }

    /**
     * @inheritdoc
     */
    public function create()
    {
        $connection = $this->connectionPool->current();

        try {
            return $connection->channel();
        } catch (\Exception $e) {
            $this->connectionPool->disposeCurrent();
            return $this->create();
        }
    }
}

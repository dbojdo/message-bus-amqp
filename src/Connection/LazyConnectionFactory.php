<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection;

use PhpAmqpLib\Connection\AMQPLazyConnection;

final class LazyConnectionFactory implements ConnectionFactory
{
    /**
     * @inheritdoc
     */
    public function create(ConnectionParams $connectionParams)
    {
        return new AMQPLazyConnection(
            $connectionParams->host(),
            $connectionParams->port(),
            $connectionParams->user(),
            $connectionParams->password(),
            $connectionParams->vHost(),
            false,
            'AMQPLAIN',
            null,
            'en_US',
            $connectionParams->timeout(),
            $connectionParams->timeout()
        );
    }
}

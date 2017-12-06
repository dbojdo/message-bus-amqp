<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Exception;

class NoViableConnectionsInPoolException extends \RuntimeException
{
    /**
     * @return NoViableConnectionsInPoolException
     */
    public static function create()
    {
        return new self('No more viable connection in the given Connection Pool');
    }
}
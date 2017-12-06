<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Registry\Exception;

class ConnectionPoolNotFoundException extends \OutOfBoundsException
{
    /**
     * @param string $targetName
     * @param int $code
     * @param \Exception|null $previous
     * @return ConnectionPoolNotFoundException
     */
    public static function fromPoolName(string $targetName, $code = 0, \Exception $previous = null)
    {
        return new self(
            sprintf('Connection Pool "%s" could not be found in this Registry.', $targetName),
            $code,
            $previous
        );
    }
}
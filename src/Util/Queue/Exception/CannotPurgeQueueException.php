<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Queue\Exception;

class CannotPurgeQueueException extends \RuntimeException
{
    /**
     * @param string $queueName
     * @param int $code
     * @param \Exception|null $previous
     * @return CannotPurgeQueueException
     */
    public static function fromQueueName($queueName, $code = 0, \Exception $previous = null)
    {
        return new self(
            sprintf('Could not purge the queue of name "%s".', $queueName),
            $code,
            $previous
        );
    }
}
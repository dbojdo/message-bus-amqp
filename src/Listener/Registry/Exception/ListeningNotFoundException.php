<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Registry\Exception;

class ListeningNotFoundException extends \OutOfBoundsException
{
    /**
     * @param string $targetName
     * @param int $code
     * @param \Exception|null $previous
     * @return ListeningNotFoundException
     */
    public static function fromListenerName(string $targetName, $code = 0, \Exception $previous = null)
    {
        return new self(
            sprintf('Listener "%s" could not be found in this Registry.', $targetName),
            $code,
            $previous
        );
    }
}
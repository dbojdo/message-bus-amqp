<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Registry\Exception;

class PublisherNotFoundException extends \OutOfBoundsException
{
    /**
     * @param string $targetName
     * @param int $code
     * @param \Exception|null $previous
     * @return PublisherNotFoundException
     */
    public static function fromPublisherName(string $targetName, $code = 0, \Exception $previous = null)
    {
        return new self(
            sprintf('Publisher "%s" could not be found in this Registry.', $targetName),
            $code,
            $previous
        );
    }
}
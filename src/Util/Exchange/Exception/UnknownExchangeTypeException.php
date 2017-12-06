<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exception;

class UnknownExchangeTypeException extends \OutOfBoundsException
{
    /**
     * @param string $type
     * @return UnknownExchangeTypeException
     */
    public static function fromType(string $type)
    {
        return new self(sprintf('Unknown exchange type "%s".', $type));
    }
}
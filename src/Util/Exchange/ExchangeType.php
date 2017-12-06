<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange;

use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exception\UnknownExchangeTypeException;

final class ExchangeType
{
    /** @var string */
    private $type;

    /**
     * ExchangeType constructor.
     * @param string $type
     */
    private function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return ExchangeType
     */
    public static function fanout(): ExchangeType
    {
        return new self('fanout');
    }

    /**
     * @return ExchangeType
     */
    public static function topic(): ExchangeType
    {
        return new self('topic');
    }

    /**
     * @return ExchangeType
     */
    public static function headers(): ExchangeType
    {
        return new self('headers');
    }

    /**
     * @return ExchangeType
     */
    public static function direct(): ExchangeType
    {
        return new self('direct');
    }

    /**
     * @param string $type
     * @return ExchangeType
     */
    public static function fromString(string $type): ExchangeType
    {
        switch($type) {
            case 'fanout':
                return self::fanout();
            case 'topic':
                return self::topic();
            case 'headers':
                return self::headers();
            case 'direct':
                return self::direct();
        }

        throw UnknownExchangeTypeException::fromType($type);
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->type;
    }
}
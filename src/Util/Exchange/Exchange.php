<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange;

final class Exchange
{
    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var bool */
    private $passive;

    /** @var bool */
    private $durable;

    /** @var bool */
    private $autoDelete;

    /** @var bool */
    private $internal;

    /**
     * Exchange constructor.
     * @param string $name
     * @param ExchangeType $type
     * @param bool $passive
     * @param bool $durable
     * @param bool $autoDelete
     * @param bool $internal
     */
    public function __construct(
        string $name,
        ExchangeType $type,
        bool $passive = false,
        bool $durable = false,
        bool $autoDelete = true,
        bool $internal = false
    ) {

        $this->name = $name;
        $this->type = $type;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->autoDelete = $autoDelete;
        $this->internal = $internal;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return ExchangeType
     */
    public function type(): ExchangeType
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isPassive(): bool
    {
        return $this->passive;
    }

    /**
     * @return bool
     */
    public function isDurable(): bool
    {
        return $this->durable;
    }

    /**
     * @return bool
     */
    public function isAutoDelete(): bool
    {
        return $this->autoDelete;
    }

    /**
     * @return bool
     */
    public function isInternal(): bool
    {
        return $this->internal;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->name();
    }
}
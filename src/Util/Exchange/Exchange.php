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

    /** @var bool */
    private $noWait;

    /** @var mixed */
    private $arguments;

    /** @var int */
    private $ticket;

    /**
     * Exchange constructor.
     * @param string $name
     * @param string $type
     * @param bool $passive
     * @param bool $durable
     * @param bool $autoDelete
     * @param bool $internal
     * @param bool $noWait
     * @param mixed $arguments
     * @param int $ticket
     */
    public function __construct(
        string $name,
        string $type,
        bool $passive = false,
        bool $durable = false,
        bool $autoDelete = true,
        bool $internal = false,
        bool $noWait = false,
        $arguments = null,
        int $ticket = null
    ) {

        $this->name = $name;
        $this->type = $type;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->autoDelete = $autoDelete;
        $this->internal = $internal;
        $this->noWait = $noWait;
        $this->arguments = $arguments;
        $this->ticket = $ticket;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function type(): string
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
     * @return bool
     */
    public function isNoWait(): bool
    {
        return $this->noWait;
    }

    /**
     * @return mixed
     */
    public function arguments()
    {
        return $this->arguments;
    }

    /**
     * @return int
     */
    public function ticket(): int
    {
        return $this->ticket;
    }
}
<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Queue;

final class Queue
{
    /** @var string */
    private $name;

    /** @var bool */
    private $passive;

    /** @var bool */
    private $durable;

    /** @var bool */
    private $exclusive;

    /** @var bool */
    private $autoDelete;

    /** @var bool */
    private $noWait;

    /** @var null */
    private $arguments;

    /** @var int */
    private $ticket;

    /**
     * Queue constructor.
     * @param string $name
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param bool $autoDelete
     * @param bool $noWait
     * @param null $arguments
     * @param int|null $ticket
     */
    public function __construct(
        string $name,
        bool $passive = false,
        bool $durable = false,
        bool $exclusive = false,
        bool $autoDelete = true,
        bool $noWait = false,
        $arguments = null,
        int $ticket = null
    ) {
        $this->name = $name;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->exclusive = $exclusive;
        $this->autoDelete = $autoDelete;
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
    public function isExclusive(): bool
    {
        return $this->exclusive;
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
    public function isNoWait(): bool
    {
        return $this->noWait;
    }

    /**
     * @return null
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
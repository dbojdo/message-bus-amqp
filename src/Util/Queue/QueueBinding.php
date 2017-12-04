<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Queue;

final class QueueBinding
{
    /** @var string */
    private $queueName;

    /** @var string */
    private $exchangeName;

    /** @var string[] */
    private $bindings;

    /** @var bool */
    private $nowait;

    /** @var null */
    private $arguments;

    /** @var int */
    private $ticket;

    /**
     * Binding constructor.
     * @param string $queueName
     * @param string $exchangeName
     * @param string[] $bindings
     * @param bool $nowait
     * @param mixed $arguments
     * @param int|null $ticket
     */
    public function __construct(
        string $queueName,
        string $exchangeName,
        array $bindings,
        bool $nowait = false,
        $arguments = null,
        int $ticket = null
    ) {
        $this->queueName = $queueName;
        $this->exchangeName = $exchangeName;
        $this->bindings = $bindings;
        $this->nowait = $nowait;
        $this->arguments = $arguments;
        $this->ticket = $ticket;
    }

    /**
     * @return string
     */
    public function queueName(): string
    {
        return $this->queueName;
    }

    /**
     * @return string
     */
    public function exchangeName(): string
    {
        return $this->exchangeName;
    }

    /**
     * @return string[]
     */
    public function bindings(): array
    {
        return $this->bindings;
    }

    /**
     * @return bool
     */
    public function isNowait(): bool
    {
        return $this->nowait;
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
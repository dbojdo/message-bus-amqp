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

    /**
     * Queue constructor.
     * @param string $name
     * @param bool $passive
     * @param bool $durable
     * @param bool $exclusive
     * @param bool $autoDelete
     */
    public function __construct(
        string $name,
        bool $passive = false,
        bool $durable = false,
        bool $exclusive = false,
        bool $autoDelete = true
    ) {
        $this->name = $name;
        $this->passive = $passive;
        $this->durable = $durable;
        $this->exclusive = $exclusive;
        $this->autoDelete = $autoDelete;
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
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->name();
    }
}

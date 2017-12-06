<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Queue;

final class QueueBinding implements \IteratorAggregate
{
    /** @var string */
    private $queueName;

    /** @var string */
    private $exchangeName;

    /** @var string[] */
    private $routingKeys;

    /**
     * Binding constructor.
     * @param string $queueName
     * @param string $exchangeName
     * @param string[] $routingKeys
     * @param bool $nowait
     * @param mixed $arguments
     * @param int|null $ticket
     */
    public function __construct(
        string $queueName,
        string $exchangeName,
        array $routingKeys,
        bool $nowait = false,
        $arguments = null,
        int $ticket = null
    ) {
        $this->queueName = $queueName;
        $this->exchangeName = $exchangeName;
        $this->routingKeys = $routingKeys;
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
    public function routingKeys(): array
    {
        return $this->routingKeys;
    }

    /**
     * @inheritdoc
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routingKeys());
    }
}
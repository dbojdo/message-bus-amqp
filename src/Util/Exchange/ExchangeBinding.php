<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange;

final class ExchangeBinding implements \IteratorAggregate
{
    /** @var string */
    private $destination;

    /** @var string */
    private $source;

    /** @var string[] */
    private $routingKeys;

    /**
     * ExchangeBinding constructor.
     * @param string $destination
     * @param string $source
     * @param string[] $routingKeys
     */
    public function __construct(
        string $destination,
        string $source,
        array $routingKeys
    ) {
        $this->destination = $destination;
        $this->source = $source;
        $this->routingKeys = $routingKeys;
    }

    /**
     * @return string
     */
    public function destination(): string
    {
        return $this->destination;
    }

    /**
     * @return string
     */
    public function source(): string
    {
        return $this->source;
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

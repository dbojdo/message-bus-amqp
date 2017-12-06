<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Registry;

use Webit\MessageBus\Infrastructure\Amqp\Publisher\AmqpPublisher;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Registry\Exception\PublisherNotFoundException;

interface PublisherRegistry
{
    /**
     * @param string $publisherName
     * @return AmqpPublisher
     * @throws PublisherNotFoundException
     */
    public function publisher(string $publisherName): AmqpPublisher;

    /**
     * @param AmqpPublisher $publisher
     * @param string $publisherName
     */
    public function registerPublisher(AmqpPublisher $publisher, string $publisherName);
}
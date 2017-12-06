<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Registry;

use Webit\MessageBus\Infrastructure\Amqp\Listener\AmqpListener;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Registry\Exception\ListeningNotFoundException;

interface ListenerRegistry
{
    /**
     * @param string $listenerName
     * @return AmqpListener
     * @throws ListeningNotFoundException
     */
    public function listener(string $listenerName): AmqpListener;

    /**
     * @param AmqpListener $listener
     * @param string $listenerName
     */
    public function registerListener(AmqpListener $listener, string $listenerName);
}

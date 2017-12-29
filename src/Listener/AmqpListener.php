<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

interface AmqpListener
{

    /**
     * @param int $timeout
     * @param bool $noLocal
     * @param bool $noAck
     * @param bool $exclusive
     * @param bool $noWait
     * @param int|null $ticket
     * @param array $arguments
     */
    public function listen(
        int $timeout = 0,
        $noLocal = false,
        $noAck = false,
        $exclusive = false,
        $noWait = false,
        int $ticket = null,
        array $arguments = []
    );
}

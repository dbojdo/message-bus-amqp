<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

interface AmqpListener
{

    /**
     * @param bool $noLocal
     * @param bool $noAck
     * @param bool $exclusive
     * @param bool $noWait
     * @param int|null $ticket
     * @param array $arguments
     */
    public function listen(
        $noLocal = false,
        $noAck = false,
        $exclusive = false,
        $noWait = false,
        int $ticket = null,
        array $arguments = []
    );
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Message\AMQPMessage;

final class KillPill
{
    public static function create(): AMQPMessage
    {
        return new AMQPMessage('kill_pill', ['type' => 'kill_pill']);
    }

    public static function isKillPill(AMQPMessage $message): bool
    {
        return $message->getBody() == 'kill_pill' || $message->get('type') == 'kill_pill';
    }
}

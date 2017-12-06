<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Queue\Exception;

use PhpAmqpLib\Exception\AMQPProtocolChannelException;

class ExceptionFactory
{
    public static function fromAmqpProtocolChannelException(AMQPProtocolChannelException $e, $queue)
    {
        if (preg_match('/no\ queue/', $e->getMessage())) {
            
        }
    }
}
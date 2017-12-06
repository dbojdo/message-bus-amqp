<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exception;

use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeBinding;

class CannotBindExchangeException extends \RuntimeException
{
    /** @var ExchangeBinding */
    private $binding;

    /**
     * @param ExchangeBinding $binding
     * @param string $routingKey
     * @param int $code
     * @param \Exception|null $previous
     * @return CannotBindExchangeException
     */
    public static function fromBindingAndRoutingKey(
        ExchangeBinding $binding,
        string $routingKey,
        $code = 0,
        \Exception $previous = null
    ) {
        $exception = new self(
            sprintf(
                sprintf('Cannot bind the exchange "%s" to the exchange "%s" by the routing key "%s".',
                    $binding->destination(),
                    $binding->source(),
                    $routingKey
                )
            ),
            $code,
            $previous
        );

        $exception->binding = $binding;

        return $exception;
    }

    /**
     * @return ExchangeBinding
     */
    public function binding(): ExchangeBinding
    {
        return $this->binding;
    }
}
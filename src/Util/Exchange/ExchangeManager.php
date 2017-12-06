<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange;

use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\ConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exception\CannotBindExchangeException;

class ExchangeManager
{
    /** @var ConnectionAwareChannelFactory */
    private $channelFactory;

    /**
     * ExchangeDeclarer constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     */
    public function __construct(ConnectionAwareChannelFactory $channelFactory)
    {
        $this->channelFactory = $channelFactory;
    }

    /**
     * @param Exchange $exchange
     * @param bool $noWait
     * @param array $arguments
     * @param int|null $ticket
     */
    public function declareExchange(Exchange $exchange, $noWait = false, array $arguments = [], int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        $channel->exchange_declare(
            $exchange->name(),
            (string)$exchange->type(),
            $exchange->isPassive(),
            $exchange->isDurable(),
            $exchange->isAutoDelete(),
            $exchange->isInternal(),
            $noWait,
            $arguments,
            $ticket
        );
    }

    /**
     * @param string $exchangeName
     * @param bool $ifUnused
     * @param bool $noWait
     * @param int $ticket
     */
    public function deleteExchange(
        string $exchangeName,
        bool $ifUnused = false,
        bool $noWait = false,
        int $ticket = null
    ) {
        $channel = $this->channelFactory->create();
        $channel->exchange_delete(
            $exchangeName,
            $ifUnused,
            $noWait,
            $ticket
        );
    }

    /**
     * @param ExchangeBinding $binding
     * @param bool $noWait
     * @param array $arguments
     * @param int|null $ticket
     */
    public function bindExchange(ExchangeBinding $binding, $noWait = false, array $arguments = [], int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        foreach ($binding as $routingKey) {
            try {
                $channel->exchange_bind(
                    $binding->destination(),
                    $binding->source(),
                    $routingKey,
                    $noWait,
                    $arguments,
                    $ticket
                );
            } catch (AMQPProtocolChannelException $e) {
                throw CannotBindExchangeException::fromBindingAndRoutingKey($binding, $routingKey, 0, $e);
            }
        }
    }

    /**
     * @param ExchangeBinding $binding
     * @param bool $noWait
     * @param array $arguments
     * @param int|null $ticket
     */
    public function unbindExchange(ExchangeBinding $binding, $noWait = false, array $arguments = [], int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        foreach ($binding as $routingKey) {
            $channel->exchange_unbind(
                $binding->destination(),
                $binding->source(),
                $routingKey,
                $noWait,
                $arguments,
                $ticket
            );
        }
    }

    /**
     * @param AMQPMessage $message
     * @param string $exchangeName
     * @param string $routingKey
     * @param bool $mandatory
     * @param bool $immediate
     * @param int|null $ticket
     */
    public function publishMessage(
        AMQPMessage $message,
        string $exchangeName,
        string $routingKey,
        bool $mandatory = false,
        bool $immediate = false,
        int $ticket = null
    ) {
        $channel = $this->channelFactory->create();
        $channel->basic_publish($message, $exchangeName, $routingKey, $mandatory, $immediate, $ticket);
    }
}

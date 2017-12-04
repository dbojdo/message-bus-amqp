<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange;

use Webit\MessageBus\Infrastructure\Amqp\Channel\ConnectionAwareChannelFactory;

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
     */
    public function declareExchange(Exchange $exchange)
    {
        $channel = $this->channelFactory->create();

        $channel->exchange_declare(
            $exchange->name(),
            $exchange->type(),
            $exchange->isPassive(),
            $exchange->isDurable(),
            $exchange->isAutoDelete(),
            $exchange->isInternal(),
            $exchange->isNoWait(),
            $exchange->arguments(),
            $exchange->ticket()
        );
    }

    /**
     * @param string $exchangeName
     * @param bool $ifUnused
     * @param bool $noWait
     * @param int $ticket
     */
    public function deleteExchange(string $exchangeName, bool $ifUnused = false, bool $noWait = false, int $ticket = null)
    {
        $channel = $this->channelFactory->create();

        $channel->exchange_delete(
            $exchangeName,
            $ifUnused,
            $noWait,
            $ticket
        );
    }
}
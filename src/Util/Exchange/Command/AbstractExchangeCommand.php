<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Command;

use Symfony\Component\Console\Command\Command;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\CachingChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\NewChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Registry\ConnectionPoolRegistry;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeManager;

abstract class AbstractExchangeCommand extends Command
{
    /** @var ConnectionPoolRegistry */
    private $registry;

    /**
     * AbstractExchangeCommand constructor.
     * @param ConnectionPoolRegistry $registry
     */
    public function __construct(ConnectionPoolRegistry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    /**
     * @param string $connectionPoolName
     * @return ExchangeManager
     */
    protected function exchangeManager(string $connectionPoolName): ExchangeManager
    {
        $pool = $this->registry->connectionPool($connectionPoolName);

        return new ExchangeManager(
            new CachingChannelConnectionAwareChannelFactory(
                new NewChannelConnectionAwareChannelFactory($pool)
            )
        );
    }
}

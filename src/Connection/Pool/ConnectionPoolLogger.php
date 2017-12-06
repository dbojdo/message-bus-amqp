<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ConnectionPoolLogger
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * ConnectionLogger constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
    }

    /**
     * @param mixed $connectionName
     */
    public function connectionDisposed($connectionName)
    {
        $this->logger->warning(sprintf('[AMQP Connection Pool] Connection "%s" has been disposed.', $connectionName));
    }

    /**
     * @param mixed$connectionName
     */
    public function connectionChosen($connectionName)
    {
        $this->logger->info(sprintf('[AMQP Connection Pool] Connection "%s" has been chosen as current.', $connectionName));
    }
}

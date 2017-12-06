<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class ConnectionPoolLoggerTest extends AbstractTestCase
{
    /** @var LoggerInterface|ObjectProphecy */
    private $logger;

    /** @var ConnectionPoolLogger */
    private $poolLogger;

    protected function setUp()
    {
        $this->logger = $this->prophesize(LoggerInterface::class);
        $this->poolLogger = new ConnectionPoolLogger($this->logger->reveal());
    }

    /**
     * @test
     */
    public function itLogsConnectionChosenEvent()
    {
        $connectionName = $this->randomString();
        $this->logger->info(Argument::containingString($connectionName))->shouldBeCalled();
        $this->logger->info(Argument::containingString('chosen'))->shouldBeCalled();

        $this->poolLogger->connectionChosen($connectionName);
    }

    /**
     * @test
     */
    public function itLogsConnectionDisposedEvent()
    {
        $connectionName = $this->randomString();
        $this->logger->warning(Argument::containingString($connectionName))->shouldBeCalled();
        $this->logger->warning(Argument::containingString('disposed'))->shouldBeCalled();

        $this->poolLogger->connectionDisposed($connectionName);
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class BasicConnectionPoolTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function itReturnsRandomConnection()
    {
        $connectionPool = new BasicConnectionPool(
            [
                $key1 = $this->randomString() => $connection1 = $this->prophesize(AMQPStreamConnection::class)->reveal(),
                $key2 = $this->randomString() => $connection2 = $this->prophesize(AMQPStreamConnection::class)->reveal()
            ]
        );

        $this->assertContains($connectionPool->current(), [$connection1, $connection2]);
    }

    /**
     * @test
     */
    public function itDisposesConnection()
    {
        $connectionPool = new BasicConnectionPool(
            $connections = [
                $key1 = $this->randomString() => $connection1 = $this->prophesize(AMQPStreamConnection::class)->reveal(),
                $key2 = $this->randomString() => $connection2 = $this->prophesize(AMQPStreamConnection::class)->reveal()
            ]
        );

        $current = $connectionPool->current();
        $next = $current === $connection1 ? $connection2 : $connection1;
        $connectionPool->disposeCurrent();

        $this->assertSame($next, $connectionPool->current());
    }

    /**
     * @test
     */
    public function itLogsConnectionEvents()
    {
        /** @var ConnectionPoolLogger|ObjectProphecy $logger */
        $logger = $this->prophesize(ConnectionPoolLogger::class);
        $connectionPool = new BasicConnectionPool(
            $connections = [
                $key1 = $this->randomString() => $connection1 = $this->prophesize(AMQPStreamConnection::class)->reveal(),
                $key2 = $this->randomString() => $connection2 = $this->prophesize(AMQPStreamConnection::class)->reveal()
            ],
            $logger->reveal()
        );

        $current = $connectionPool->current();
        $currentName = array_search($current, $connections, true);

        $logger->connectionChosen($currentName)->shouldBeCalled();

        // on dispose logs disposed connection and next connection to be used
        $logger->connectionDisposed($currentName)->shouldBeCalled();
        $connectionPool->disposeCurrent();
    }
}

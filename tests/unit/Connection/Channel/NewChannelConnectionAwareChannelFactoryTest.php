<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Channel;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPool;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Exception\NoViableConnectionsInPoolException;

class NewChannelConnectionAwareChannelFactoryTest extends AbstractTestCase
{
    /** @var ConnectionPool|ObjectProphecy */
    private $connectionPool;

    /** @var NewChannelConnectionAwareChannelFactory */
    private $factory;

    protected function setUp()
    {
        $this->connectionPool = $this->prophesize(ConnectionPool::class);
        $this->factory = new NewChannelConnectionAwareChannelFactory($this->connectionPool->reveal());
    }

    /**
     * @test
     */
    public function itCreatesChannelFromCurrentConnection()
    {
        /** @var AMQPStreamConnection $connection */
        $connection = $this->prophesize(AMQPStreamConnection::class);
        $this->connectionPool->current()->willReturn($connection);

        $channel = $this->prophesize(AMQPChannel::class)->reveal();
        $connection->channel()->willReturn($channel);

        $this->assertSame($channel, $this->factory->create());
    }

    /**
     * @throws \Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Exception\NoViableConnectionsInPoolException
     */
    public function itRethrowsNoViableConnectionsException()
    {
        $exception = $this->prophesize(NoViableConnectionsInPoolException::class)->reveal();
        $this->connectionPool->current()->willThrow($exception);

        $this->factory->create();
    }

    /**
     * @test
     */
    public function itDisposesConnectionOnChannelCreationException()
    {
        /** @var AMQPStreamConnection $connection */
        $connection1 = $this->prophesize(AMQPStreamConnection::class);
        $this->connectionPool->current()->willReturn($connection1);

        $connection1->channel()->willThrow($this->prophesize(\Exception::class)->reveal());

        /** @var AMQPStreamConnection $connection */
        $connection2 = $this->prophesize(AMQPStreamConnection::class);
        $this->connectionPool->current()->willReturn($connection2);

        $channel = $this->prophesize(AMQPChannel::class)->reveal();
        $connection2->channel()->willReturn($channel);

        $this->assertSame($channel, $this->factory->create());
    }
}

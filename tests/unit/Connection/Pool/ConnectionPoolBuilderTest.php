<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Pool;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Connection\ConnectionFactory;
use Webit\MessageBus\Infrastructure\Amqp\Connection\ConnectionParams;

class ConnectionPoolBuilderTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function itBuildsConnectionPool()
    {
        $connectionFactory = new MockConnectionFactory(
            [
                $connection1 = $this->prophesize(AMQPStreamConnection::class),
                $connection2 = $this->prophesize(AMQPStreamConnection::class)
            ]
        );

        /** @var LoggerInterface|ObjectProphecy $logger */
        $logger = $this->prophesize(LoggerInterface::class);
        $builder = ConnectionPoolBuilder::create();
        $builder->setConnectionFactory($connectionFactory);
        $builder->setLogger($logger->reveal());

        $builder->registerConnection($params1 = $this->randomConnectionParams());
        $builder->registerConnection($params2 = $this->randomConnectionParams(), $key2 = 'connection2');

        $key1 = (string)$params1;

        $pool = $builder->build();
        $this->assertInstanceOf(BasicConnectionPool::class, $pool);

        $current = $pool->current();

        $logger->info(Argument::containingString($key1))->shouldBeCalled();
        $logger->info(Argument::containingString($key2))->shouldBeCalled();

        list($next, $disposedKey) = $current === $connection1->reveal() ? [$connection2->reveal(), $key1] : [$connection1->reveal(), $key2];
        $logger->warning(Argument::containingString($disposedKey))->shouldBeCalled();

        $pool->disposeCurrent();
        $this->assertSame($next, $pool->current());
    }

    private function randomConnectionParams()
    {
        return new ConnectionParams(
            $this->randomString(),
            $this->randomString(),
            $this->randomString(),
            $this->randomString(),
            $this->randomString()
        );
    }
}

class MockConnectionFactory extends TestCase implements ConnectionFactory
{
    /**
     * @var ObjectProphecy[]|AMQPStreamConnection[]
     */
    private $connections;

    /**
     * MockConnectionFactory constructor.
     * @param AMQPStreamConnection[]|ObjectProphecy[] $connections
     */
    public function __construct($connections)
    {
        $this->connections = $connections;
    }

    /**
     * @inheritdoc
     */
    public function create(ConnectionParams $connectionParams)
    {
        $connection = array_shift($this->connections);
        if ($connection) {
            return $connection->reveal();
        }

        throw new \Exception('No more connections to be constructed.');
    }
}

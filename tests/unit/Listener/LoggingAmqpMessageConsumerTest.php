<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;

class LoggingAmqpMessageConsumerTest extends AbstractTestCase
{
    /** @var ConsumerLogger|ObjectProphecy */
    private $logger;

    /** @var AmqpMessageConsumer|ObjectProphecy */
    private $innerConsumer;

    /** @var LoggingAmqpMessageConsumer */
    private $consumer;

    protected function setUp()
    {
        $this->logger = $this->prophesize(ConsumerLogger::class);
        $this->innerConsumer = $this->prophesize(AmqpMessageConsumer::class);
        $this->consumer = new LoggingAmqpMessageConsumer(
            $this->innerConsumer->reveal(),
            $this->logger->reveal()
        );
    }

    /**
     * @test
     */
    public function itLogsPreAndPostConsumption()
    {
        $message = $this->randomAmqpMessage()->reveal();

        $this->innerConsumer->consume($message)->shouldBeCalled();

        $this->logger->logPreConsume($message)->shouldBeCalled();
        $this->logger->logPostConsume($message)->shouldBeCalled();
        $this->logger->logConsumptionException(Argument::any())->shouldNotBeCalled();

        $this->innerConsumer->consume($message)->shouldBeCalled();
        $this->consumer->consume($message);
    }

    /**
     * @test
     * @expectedException \Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException
     */
    public function itLogsException()
    {
        $message = $this->randomAmqpMessage()->reveal();

        $exception = $this->prophesize(AmqpMessageConsumptionException::class)->reveal();
        $this->innerConsumer->consume($message)->willThrow($exception);

        $this->logger->logPreConsume($message)->shouldBeCalled();
        $this->logger->logPostConsume($message)->shouldNotBeCalled();
        $this->logger->logConsumptionException($exception)->shouldBeCalled();

        $this->consumer->consume($message);
    }
}

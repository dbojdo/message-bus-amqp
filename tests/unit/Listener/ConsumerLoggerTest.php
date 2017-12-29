<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Log\LoggerInterface;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\AmqpMessageConsumptionException;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\CouldNotConsumeAmqpMessageException;

class ConsumerLoggerTest extends AbstractTestCase
{
    /** @var LoggerInterface|ObjectProphecy */
    private $loger;

    /** @var ConsumerLogger */
    private $consumerLogger;

    protected function setUp()
    {
        $this->loger = $this->prophesize(LoggerInterface::class);
        $this->consumerLogger = new ConsumerLogger($this->loger->reveal());
    }

    /**
     * @test
     */
    public function itLogsPreConsume()
    {
        $message = $this->randomAmqpMessage();

        $message->get('type')->willReturn($messageType = $this->randomString());
        $message->getBody()->willReturn($messageContent = $this->randomString());

        $this->loger->info(
            Argument::containingString('Consuming a message'),
            [
                'messageType' => $messageType,
                'messageBody' => $messageContent
            ]
        )->shouldBeCalled();

        $this->consumerLogger->logPreConsume($message->reveal());

    }

    /**
     * @test
     */
    public function itLogsPostConsume()
    {
        $message = $this->randomAmqpMessage();

        $message->get('type')->willReturn($messageType = $this->randomString());
        $message->getBody()->willReturn($messageContent = $this->randomString());

        $this->loger->info(
            Argument::containingString('Message has been consumed'),
            [
                'messageType' => $messageType,
                'messageBody' => $messageContent
            ]
        )->shouldBeCalled();

        $this->consumerLogger->logPostConsume($message->reveal());
    }

    /**
     * @test
     */
    public function itLogsConsumptionException()
    {
        $message = $this->randomAmqpMessage();
        $message->get('type')->willReturn($messageType = $this->randomString());
        $message->getBody()->willReturn($messageContent = $this->randomString());

        /** @var AmqpMessageConsumptionException|ObjectProphecy $exception */
        $exception = CouldNotConsumeAmqpMessageException::forMessage($message->reveal());

        $this->loger->alert(
            Argument::containingString('Error during message consumption'),
            [
                'messageType' => $exception->amqpMessage()->get('type'),
                'messageBody' => $exception->amqpMessage()->getBody(),
                'exceptionClass' => get_class($exception),
                'exceptionMessage' => $exception->getMessage(),
                'exceptionTrace' => $exception->getTraceAsString()
            ]
        )->shouldBeCalled();

        $this->consumerLogger->logConsumptionException($exception);
    }
}

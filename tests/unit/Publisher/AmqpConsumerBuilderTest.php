<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use Psr\Log\LoggerInterface;
use Webit\MessageBus\Consumer;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Listener\AmqpConsumerBuilder;
use Webit\MessageBus\Infrastructure\Amqp\Listener\KillPillAmqpMessageConsumer;
use Webit\MessageBus\Infrastructure\Amqp\Listener\LoggingAmqpMessageConsumer;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Message\MessageFactory;
use Webit\MessageBus\Infrastructure\Amqp\Listener\MessageConsumingAmqpMessageConsumer;

class AmqpConsumerBuilderTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function itBuildsSupportsKillPillByDefault()
    {
        $builder = AmqpConsumerBuilder::create();
        $this->assertInstanceOf(KillPillAmqpMessageConsumer::class, $builder->build());
    }

    /**
     * @test
     */
    public function itBuildsAmqpConsumerWithNoFeedback()
    {
        $builder = AmqpConsumerBuilder::create();
        $builder->shouldSendFeedback(false);
        $builder->supportKillPill(false);
        $this->assertInstanceOf(MessageConsumingAmqpMessageConsumer::class, $builder->build());
    }

    /**
     * @test
     */
    public function itConfiguresConsumer()
    {
        $builder = AmqpConsumerBuilder::create();
        $builder->shouldSendFeedback(false);

        $consumer = $this->prophesize(Consumer::class);

        $amqpMessage = $this->randomAmqpMessage();
        $amqpMessage->getBody()->willReturn($body = $this->randomString());
        $amqpMessage->get('type')->willReturn($type = $this->randomString());

        /** @var MessageFactory $messageFactory */
        $messageFactory = $this->prophesize(MessageFactory::class);
        $messageFactory->create($amqpMessage)->willReturn($message = $this->randomMessage())->shouldBeCalled();
        $builder->setMessageFactory($messageFactory->reveal());
        $builder->setConsumer($consumer->reveal());

        $consumer->consume($message)->shouldBeCalled();

        $amqpConsumer = $builder->build();
        $amqpConsumer->consume($amqpMessage->reveal());
    }

    /**
     * @test
     */
    public function itBuildsAmqpConsumerWithLogger()
    {
        $builder = AmqpConsumerBuilder::create();
        $builder->setLogger($this->prophesize(LoggerInterface::class)->reveal());
        $this->assertInstanceOf(LoggingAmqpMessageConsumer::class, $builder->build());
    }

    /**
     * @test
     */
    public function itAllowToSetMessageFactory()
    {
        $builder = AmqpConsumerBuilder::create();
        $builder->shouldSendFeedback(false);

        $amqpMessage = $this->randomAmqpMessage()->reveal();

        /** @var MessageFactory $messageFactory */
        $messageFactory = $this->prophesize(MessageFactory::class);
        $messageFactory->create($amqpMessage)->willReturn($message = $this->randomMessage())->shouldBeCalled();

        $builder->setMessageFactory($messageFactory->reveal());
        $consumer = $builder->build();

        $consumer->consume($amqpMessage);
    }
}

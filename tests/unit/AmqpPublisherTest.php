<?php

namespace Webit\MessageBus\Infrastructure\Amqp;

use PhpAmqpLib\Message\AMQPMessage;
use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Exception\MessagePublicationException;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Message\AmqpMessageFactory;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\PublicationTarget;

class AmqpPublisherTest extends AbstractTestCase
{
    /** @var AmqpMessageFactory|ObjectProphecy */
    private $amqpMessageFactory;

    /** @var PublicationTarget|ObjectProphecy */
    private $publicationTarget;

    /** @var AmqpPublisher */
    private $sut;

    protected function setUp()
    {
        $this->amqpMessageFactory = $this->prophesize(AmqpMessageFactory::class);
        $this->publicationTarget = $this->prophesize(PublicationTarget::class);
        $this->sut = new AmqpPublisher(
            $this->amqpMessageFactory->reveal(),
            $this->publicationTarget->reveal()
        );
    }

    /**
     * @test
     */
    public function itPublishesMessageUsingGivenAmpqTarget()
    {
        $message = $this->randomMessage();

        $amqpMessage = $this->prophesize(AMQPMessage::class);
        $this->amqpMessageFactory->create($message)->willReturn($amqpMessage->reveal())->shouldBeCalled();
        $this->publicationTarget->publish($amqpMessage->reveal())->shouldBeCalled();

        $this->sut->publish($message);
    }

    /**
     * @test
     */
    public function itWrapsAnyExceptionWithPublicationException()
    {
        $message = $this->randomMessage();

        $amqpMessage = $this->prophesize(AMQPMessage::class);
        $this->amqpMessageFactory->create($message)->willReturn($amqpMessage->reveal())->shouldBeCalled();
        $this->publicationTarget->publish($amqpMessage->reveal())->willThrow($this->prophesize(\Exception::class)->reveal());

        $this->expectException(MessagePublicationException::class);

        $this->sut->publish($message);
    }
}

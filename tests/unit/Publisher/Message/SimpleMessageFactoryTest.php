<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Message;

use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class SimpleMessageFactoryTest extends AbstractTestCase
{
    /** @var SimpleAmqpMessageFactory */
    private $factory;

    protected function setUp()
    {
        $this->factory = new SimpleAmqpMessageFactory();
    }

    /**
     * @test
     */
    public function itCreatesAmqpMessageFromMessage()
    {
        $message = $this->randomMessage();
        $amqpMessage = $this->factory->create($message);

        $this->assertEquals($message->content(), $amqpMessage->body);
        $this->assertEquals($message->type(), $amqpMessage->get('type'));
        $this->assertLessThanOrEqual(time(), $amqpMessage->get('timestamp'));
    }
}

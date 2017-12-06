<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Message;

use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;
use Webit\MessageBus\Message;

class SimpleMessageFactoryTest extends AbstractTestCase
{
    /**
     * @var SimpleMessageFactory
     */
    private $factory;

    protected function setUp()
    {
        $this->factory = new SimpleMessageFactory();
    }

    /**
     * @test
     */
    public function itCreateMessage()
    {
        $amqpMessage = $this->randomAmqpMessage();
        $amqpMessage->get('type')->willReturn($type = $this->randomString());
        $amqpMessage->getBody()->willReturn($body = $this->randomString());

        $this->assertEquals(new Message($type, $body), $this->factory->create($amqpMessage->reveal()));
    }
}

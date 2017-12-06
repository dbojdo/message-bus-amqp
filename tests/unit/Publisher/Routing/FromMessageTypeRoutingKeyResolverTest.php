<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing;

use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class FromMessageTypeRoutingKeyResolverTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function itReturnsRoutingKeyFromMessageType()
    {
        $resolver = new FromMessageTypeRoutingKeyResolver();
        $message = $this->randomAmqpMessage();
        $message->get('type')->willReturn($type = $this->randomString());

        $this->assertEquals($type, $resolver->resolve($message->reveal()));
    }

    /**
     * @test
     */
    public function itReturnsEmptyStringIfTypeNotSet()
    {
        $resolver = new FromMessageTypeRoutingKeyResolver();
        $message = $this->randomAmqpMessage();
        $message->get('type')->willThrow($this->prophesize(\OutOfBoundsException::class)->reveal());

        $this->assertEquals('', $resolver->resolve($message->reveal()));
    }
}

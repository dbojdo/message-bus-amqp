<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher\Routing;

use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class VoidRoutingKeyResolverTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function itReturnsEmptyString()
    {
        $routingKeyResolver = new VoidRoutingKeyResolver();
        $this->assertSame('', $routingKeyResolver->resolve($this->randomAmqpMessage()->reveal()));
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class MultiPublicationTargetTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function itPublishesToMultipleTargets()
    {
        $message = $this->randomAmqpMessage()->reveal();

        for ($i = 0; $i < 3; $i++) {
            $target = $this->prophesize(PublicationTarget::class);
            $target->publish($message)->shouldBeCalled();
            $targets[] = $target->reveal();
        }

        $target = new MultiPublicationTarget($targets);

        $target->publish($message);
    }
}

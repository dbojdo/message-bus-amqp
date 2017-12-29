<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class KillPillAmqpMessageConsumerTest extends AbstractTestCase
{
    /** @var AmqpMessageConsumer|ObjectProphecy */
    private $innerConsumer;

    /** @var KillPillAmqpMessageConsumer */
    private $consumer;

    protected function setUp()
    {
        $this->innerConsumer = $this->prophesize(AmqpMessageConsumer::class);
        $this->consumer = new KillPillAmqpMessageConsumer($this->innerConsumer->reveal());
    }

    /**
     * @test
     * @expectedException \Webit\MessageBus\Infrastructure\Amqp\Listener\Exception\KillPillReceivedException
     */
    public function shouldThrowExceptionOnKillPill()
    {
        $killPill = KillPill::create();

        $this->innerConsumer->consume($killPill)->shouldNotBeCalled();

        $this->consumer->consume($killPill);
    }

    /**
     * @test
     */
    public function shouldConsumeNonKillPill()
    {
        $message = $this->randomAmqpMessage()->reveal();
        $this->innerConsumer->consume($message)->shouldBeCalled();
        $this->consumer->consume($message);
    }
}

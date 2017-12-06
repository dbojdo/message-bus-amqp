<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Channel;

use PhpAmqpLib\Channel\AMQPChannel;
use Prophecy\Prophecy\ObjectProphecy;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class CachingChannelConnectionAwareChannelFactoryTest extends AbstractTestCase
{
    /** @var ChannelCache|ObjectProphecy */
    private $cache;

    /** @var ConnectionAwareChannelFactory|ObjectProphecy */
    private $innerFactory;

    /** @var CachingChannelConnectionAwareChannelFactory */
    private $factory;

    protected function setUp()
    {
        $this->cache = $this->prophesize(ChannelCache::class);
        $this->innerFactory = $this->prophesize(ConnectionAwareChannelFactory::class);
        $this->factory = new CachingChannelConnectionAwareChannelFactory(
            $this->innerFactory->reveal(),
            $this->cache->reveal()
        );
    }

    /**
     * @test
     */
    public function itCreatesChannelIfNotInCache()
    {
        $this->cache->hasChannel()->willReturn(false);

        $channel = $this->prophesize(AMQPChannel::class)->reveal();
        $this->innerFactory->create()->willReturn($channel);
        $this->cache->save($channel)->shouldBeCalled();
        $this->assertSame($channel, $this->factory->create());
    }

    /**
     * @test
     */
    public function itReadsChannelFromCache()
    {
        $this->cache->hasChannel()->willReturn(true);

        $channel = $this->prophesize(AMQPChannel::class)->reveal();
        $this->innerFactory->create()->shouldNotBeCalled();
        $this->cache->fetch()->willReturn($channel);
        $this->assertSame($channel, $this->factory->create());
    }
}

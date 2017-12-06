<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Channel;

use PhpAmqpLib\Channel\AMQPChannel;
use Webit\MessageBus\Infrastructure\Amqp\AbstractTestCase;

class ChannelCacheTest extends AbstractTestCase
{
    /** @var ChannelCache */
    private $channelCache;

    protected function setUp()
    {
        $this->channelCache = new ChannelCache();
    }

    /**
     * @test
     */
    public function itSavesAndReadsTheChannelFromCache()
    {
        $channel = $this->prophesize(AMQPChannel::class)->reveal();

        $this->assertFalse($this->channelCache->hasChannel());
        $this->assertNull($this->channelCache->fetch());

        $this->channelCache->save($channel);
        $this->assertTrue($this->channelCache->hasChannel());
        $this->assertSame($channel, $this->channelCache->fetch());
    }

    /**
     * @test
     */
    public function itClearsTheCache()
    {
        $channel = $this->prophesize(AMQPChannel::class)->reveal();
        $this->channelCache->save($channel);
        $this->channelCache->clear();

        $this->assertFalse($this->channelCache->hasChannel());
    }
}

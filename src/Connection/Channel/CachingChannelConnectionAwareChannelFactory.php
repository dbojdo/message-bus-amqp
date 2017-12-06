<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Channel;

final class CachingChannelConnectionAwareChannelFactory implements ConnectionAwareChannelFactory
{
    /** @var ConnectionAwareChannelFactory */
    private $channelFactory;

    /** @var ChannelCache */
    private $cache;

    /**
     * CachingChannelConnectionAwareChannelFactory constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     * @param ChannelCache $cache
     */
    public function __construct(ConnectionAwareChannelFactory $channelFactory, ChannelCache $cache = null)
    {
        $this->channelFactory = $channelFactory;
        $this->cache = $cache ?: new ChannelCache();
    }

    /**
     * @inheritdoc
     */
    public function create()
    {
        if (!$this->cache->hasChannel()) {
            $channel = $this->channelFactory->create();
            $this->cache->save($channel);

            return $channel;
        }

        return $this->cache->fetch();
    }
}

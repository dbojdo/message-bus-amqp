<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Channel;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;

class ChannelCachingConnectionAwareChannelFactory implements ConnectionAwareChannelFactory
{
    /** @var ConnectionAwareChannelFactory */
    private $innerFactory;

    /** @var Cache */
    private $cache;

    /** @var string */
    private $cacheKey;

    /**
     * ChannelCachingConnectionAwareChannelFactory constructor.
     * @param ConnectionAwareChannelFactory $innerFactory
     * @param ArrayCache $cache
     */
    public function __construct(ConnectionAwareChannelFactory $innerFactory, ArrayCache $cache = null)
    {
        $this->innerFactory = $innerFactory;
        $this->cache = $cache ?: new ArrayCache();
        $this->cacheKey = $this->generateCacheKey();
    }

    /**
     * @inheritdoc
     */
    public function create()
    {
        if (!$this->cache->has($this->cacheKey)) {
            $channel = $this->innerFactory->create();
            $this->cache->save($this->cacheKey, $channel);

            return $channel;
        }

        return $this->cache->fetch($this->cacheKey);
    }

    /**
     * @return string
     */
    private function generateCacheKey()
    {
        return md5(mt_rand(0, 1000000).microtime());
    }
}
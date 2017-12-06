<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Connection\Channel;

use Doctrine\Common\Cache\ArrayCache;
use PhpAmqpLib\Channel\AMQPChannel;

class ChannelCache
{
    /**
     * @var ArrayCache
     */
    private $cache;

    /**
     * @var string
     */
    private $cacheKey;

    /**
     * ChannelCache constructor.
     */
    public function __construct()
    {
        $this->cache = new ArrayCache();
        $this->cacheKey = md5(mt_rand(0, PHP_INT_MAX).microtime());
    }

    /**
     * @param AMQPChannel $channel
     */
    public function save(AMQPChannel $channel)
    {
        $this->cache->save($this->cacheKey, $channel);
    }

    /**
     * @return AMQPChannel|null
     */
    public function fetch()
    {
        return $this->hasChannel() ? $this->cache->fetch($this->cacheKey) : null;
    }

    /**
     * @return bool
     */
    public function hasChannel()
    {
        return $this->cache->contains($this->cacheKey);
    }

    public function clear()
    {
        $this->cache->delete($this->cacheKey);
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Queue;

use Webit\MessageBus\Infrastructure\Amqp\Channel\ConnectionAwareChannelFactory;

class QueueManager
{
    /** @var ConnectionAwareChannelFactory */
    private $channelFactory;

    /**
     * QueueDeclarer constructor.
     * @param ConnectionAwareChannelFactory $channelFactory
     */
    public function __construct(ConnectionAwareChannelFactory $channelFactory)
    {
        $this->channelFactory = $channelFactory;
    }

    /**
     * @param Queue $queue
     */
    public function declareQueue(Queue $queue)
    {
        $channel = $this->channelFactory->create();
        $channel->queue_declare(
            $queue->name(),
            $queue->isPassive(),
            $queue->isDurable(),
            $queue->isExclusive(),
            $queue->isAutoDelete(),
            $queue->isNoWait(),
            $queue->arguments(),
            $queue->ticket()
        );
    }

    /**
     * @param QueueBinding $queueBinding
     */
    public function bindQueue(QueueBinding $queueBinding)
    {
        $channel = $this->channelFactory->create();
        foreach ($queueBinding->bindings() as $bindingPattern) {
            $channel->queue_bind(
                $queueBinding->queueName(),
                $queueBinding->exchangeName(),
                $bindingPattern,
                $queueBinding->isNowait(),
                $queueBinding->arguments(),
                $queueBinding->ticket()
            );
        }
    }

    /**
     * @param QueueBinding $queueBinding
     */
    public function unbindQueue(QueueBinding $queueBinding)
    {
        $channel = $this->channelFactory->create();

        foreach ($queueBinding->bindings() as $bindingPattern) {
            $channel->queue_unbind(
                $queueBinding->queueName(),
                $queueBinding->exchangeName(),
                $bindingPattern,
                $queueBinding->arguments(),
                $queueBinding->ticket()
            );
        }
    }

    /**
     * @param string $queueName
     * @param bool $ifUnused
     * @param bool $ifEmpty
     * @param bool $noWait
     * @param int $ticket
     */
    public function deleteQueue($queueName, $ifUnused = false, $ifEmpty = false, $noWait = false, int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        $channel->queue_delete(
            $queueName,
            $ifUnused,
            $ifEmpty,
            $noWait,
            $ticket
        );
    }

    /**
     * @param $queueName
     * @param bool $noWait
     * @param int $ticket
     */
    public function purge($queueName, $noWait = false, int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        $channel->queue_purge(
            $queueName,
            $noWait,
            $ticket
        );
    }
}

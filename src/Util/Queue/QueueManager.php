<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Queue;

use PhpAmqpLib\Exception\AMQPProtocolChannelException;
use PhpAmqpLib\Message\AMQPMessage;
use Webit\MessageBus\Infrastructure\Amqp\Channel\ConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\Exception\CannotPurgeQueueException;

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
     * @param bool $noWait
     * @param array $arguments
     * @param int|null $ticket
     */
    public function declareQueue(Queue $queue, $noWait = false, array $arguments = [], int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        $channel->queue_declare(
            $queue->name(),
            $queue->isPassive(),
            $queue->isDurable(),
            $queue->isExclusive(),
            $queue->isAutoDelete(),
            $noWait,
            $arguments,
            $ticket
        );
    }

    /**
     * @param QueueBinding $queueBinding
     * @param bool $noWait
     * @param array $arguments
     * @param int|null $ticket
     */
    public function bindQueue(QueueBinding $queueBinding, $noWait = false, array $arguments = [], int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        foreach ($queueBinding->routingKeys() as $bindingPattern) {
            $channel->queue_bind(
                $queueBinding->queueName(),
                $queueBinding->exchangeName(),
                $bindingPattern,
                $noWait,
                $arguments,
                $ticket
            );
        }
    }

    /**
     * @param QueueBinding $queueBinding
     * @param array $arguments
     * @param int|null $ticket
     */
    public function unbindQueue(QueueBinding $queueBinding, array $arguments = [], int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        foreach ($queueBinding as $routingKey) {
            $channel->queue_unbind(
                $queueBinding->queueName(),
                $queueBinding->exchangeName(),
                $routingKey,
                $arguments,
                $ticket
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
        try {
            $channel->queue_purge(
                $queueName,
                $noWait,
                $ticket
            );
        } catch (AMQPProtocolChannelException $e) {
            throw CannotPurgeQueueException::fromQueueName($queueName, 0, $e);
        }
    }

    /**
     * @param string $queueName
     * @param bool $acknowledge
     * @param int|null $ticket
     * @return AMQPMessage
     */
    public function readMessage($queueName, $acknowledge = true, int $ticket = null)
    {
        $channel = $this->channelFactory->create();
        $result = $channel->basic_get($queueName, !$acknowledge, $ticket);

        return $result;
    }

    /**
     * @param string $queueName
     * @param AMQPMessage $message
     * @param bool $mandatory
     * @param bool $immediate
     * @param bool|null $ticket
     */
    public function publishMessage(
        string $queueName,
        AMQPMessage $message,
        bool $mandatory = false,
        bool $immediate = false,
        bool $ticket = null
    ) {
        $channel = $this->channelFactory->create();
        $channel->basic_publish($message, '', $queueName, $mandatory, $immediate, $ticket);
    }
}

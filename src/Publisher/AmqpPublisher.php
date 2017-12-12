<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use Webit\MessageBus\Exception\MessagePublicationException;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Message\AmqpMessageFactory;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Message\SimpleAmqpMessageFactory;
use Webit\MessageBus\Message;
use Webit\MessageBus\Publisher;

final class AmqpPublisher implements Publisher
{
    /** @var AmqpMessageFactory */
    private $messageFactory;

    /** @var PublicationTarget */
    private $target;

    /**
     * AmqpPublisher constructor.
     * @param PublicationTarget $target
     * @param AmqpMessageFactory $messageFactory
     */
    public function __construct(PublicationTarget $target, AmqpMessageFactory $messageFactory = null)
    {
        $this->target = $target;
        $this->messageFactory = $messageFactory ?: new SimpleAmqpMessageFactory();
    }

    /**
     * @inheritdoc
     */
    public function publish(Message $message)
    {
        try {
            $this->target->publish(
                $this->messageFactory->create($message)
            );
        } catch (\Exception $e) {
            throw MessagePublicationException::forMessage($message, 0, $e);
        }
    }
}

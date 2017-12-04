<?php

namespace Webit\MessageBus\Infrastructure\Amqp;

use Webit\MessageBus\Exception\MessagePublicationException;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\Message\AmqpMessageFactory;
use Webit\MessageBus\Infrastructure\Amqp\Publisher\PublicationTarget;
use Webit\MessageBus\Message;
use Webit\MessageBus\Publisher;

class AmqpPublisher implements Publisher
{
    /** @var AmqpMessageFactory */
    private $messageFactory;

    /** @var PublicationTarget */
    private $target;

    /**
     * AmqpPublisher constructor.
     * @param AmqpMessageFactory $messageFactory
     * @param PublicationTarget $target
     */
    public function __construct(AmqpMessageFactory $messageFactory, PublicationTarget $target)
    {
        $this->messageFactory = $messageFactory;
        $this->target = $target;
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

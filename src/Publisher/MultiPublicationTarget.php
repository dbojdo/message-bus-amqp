<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Publisher;

use PhpAmqpLib\Message\AMQPMessage;

final class MultiPublicationTarget implements PublicationTarget
{
    /** @var PublicationTarget[] */
    private $targets;

    /**
     * MulitPublishTarget constructor.
     * @param PublicationTarget[] $targets
     */
    public function __construct(array $targets)
    {
        $this->targets = $targets;
    }

    /**
     * @inheritdoc
     */
    public function publish(AMQPMessage $message)
    {
        foreach ($this->targets as $target) {
            $target->publish($message);
        }
    }
}

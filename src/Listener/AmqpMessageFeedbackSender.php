<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class AmqpMessageFeedbackSender
{
    /**
     * @param AMQPMessage $message
     */
    public function acknowledge(AMQPMessage $message)
    {
        /** @var AMQPChannel $channel */
        list($channel, $deliveryTag) = $this->channelAndDeliveryTag($message);
        $channel->basic_ack($deliveryTag);
    }

    /**
     * @param AMQPMessage $message
     * @param bool $requeue
     */
    public function nacknowledge(AMQPMessage $message, bool $requeue = false)
    {
        /** @var AMQPChannel $channel */
        list($channel, $deliveryTag) = $this->channelAndDeliveryTag($message);
        $channel->basic_nack($deliveryTag, false, $requeue);
    }

    /**
     * @param AMQPMessage $message
     * @param bool $requeue
     */
    public function reject(AMQPMessage $message, bool $requeue = true)
    {
        /** @var AMQPChannel $channel */
        list($channel, $deliveryTag) = $this->channelAndDeliveryTag($message);
        $channel->basic_reject($deliveryTag, $requeue);
    }

    /**
     * @param AMQPMessage $message
     * @return array
     */
    private function channelAndDeliveryTag(AMQPMessage $message)
    {
        $channel = $message->delivery_info['channel'];
        return [$channel, $message->delivery_info['delivery_tag']];
    }
}
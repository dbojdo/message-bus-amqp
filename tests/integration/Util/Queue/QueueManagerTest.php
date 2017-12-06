<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Integration\Util\Queue;

use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\NewChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Integration\AbstractIntegrationTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeType;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\Exception\CannotPurgeQueueException;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\QueueBinding;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\QueueManager;

class QueueManagerTest extends AbstractIntegrationTestCase
{
    /** @var QueueManager */
    private $queueManager;

    protected function setUp()
    {
        $this->queueManager = new QueueManager(
            new NewChannelConnectionAwareChannelFactory($this->connectionPool())
        );
    }

    /**
     * @test
     */
    public function itCreatesQueue()
    {
        $this->queueManager->declareQueue(
            $queue = $this->queue()
        );

        $this->queueManager->purge($queue->name());
        $this->assertTrue(true, 'Queue could not be declared.');
    }

    /**
     * @test
     */
    public function itDeletesQueue()
    {
        $this->queueManager->declareQueue(
            $queue = $this->queue()
        );

        $this->queueManager->deleteQueue($queue->name());

        try {
            $this->queueManager->purge($queue->name());
        } catch (CannotPurgeQueueException $e) {
            $this->assertTrue(true);
            return;
        }

        $this->assertTrue(false, sprintf('Queue "%s" has not been deleted.', $queue->name()));
    }

    /**
     * @test
     */
    public function itPublishesAndReadsMessageFromQueue()
    {
        $this->queueManager->declareQueue($queue = $this->queue());
        $this->queueManager->publishMessage($queue->name(), $publishedMessage = $this->randomAmqpMessage());

        $readMessage = $this->queueManager->readMessage($queue->name());

        $this->assertEquals($publishedMessage->body, $readMessage->body);
    }

    /**
     * @test
     */
    public function itBindsToTheExchange()
    {
        $exchangeManager = $this->exchangeManager();
        $exchangeManager->declareExchange($exchange = $this->exchange(null, ExchangeType::topic()));
        $this->queueManager->declareQueue($queue = $this->queue());

        $this->queueManager->bindQueue(new QueueBinding(
            $queue->name(),
            $exchange->name(),
            ['test.*']
        ));

        // message should be delivered to the queue
        $exchangeManager->publishMessage(
            $publishedMessage = $this->randomAmqpMessage(),
            $exchange->name(),
            $routingKey = 'test.type'
        );

        $readMessage = $this->queueManager->readMessage($queue->name());

        $this->assertNotNull($readMessage, 'No message could not be found in the Queue.');
        $this->assertEquals($publishedMessage->body, $readMessage->body);

        // message should not be delivered because of not matching routing key
        $exchangeManager->publishMessage(
            $publishedMessage = $this->randomAmqpMessage(),
            $exchange->name(),
            $routingKey = 'no-test.type'
        );

        $readMessage = $this->queueManager->readMessage($queue->name());
        $this->assertNull($readMessage, 'No message should be found in the Queue.');
    }
}

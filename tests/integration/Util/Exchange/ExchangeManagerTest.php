<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Integration\Util\Exchange;

use Webit\MessageBus\Infrastructure\Amqp\Connection\Channel\NewChannelConnectionAwareChannelFactory;
use Webit\MessageBus\Infrastructure\Amqp\Integration\AbstractIntegrationTestCase;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exception\CannotBindExchangeException;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeBinding;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeManager;
use Webit\MessageBus\Infrastructure\Amqp\Util\Queue\QueueBinding;

class ExchangeManagerTest extends AbstractIntegrationTestCase
{
    /** @var ExchangeManager */
    private $exchangeManager;

    protected function setUp()
    {
        $this->exchangeManager = new ExchangeManager(
            new NewChannelConnectionAwareChannelFactory(
                $this->connectionPool()
            )
        );
    }

    /**
     * @test
     */
    public function itDeclaresExchange()
    {
        $this->exchangeManager->declareExchange(
            $exchange = $this->exchange()
        );
        $this->exchangeManager->declareExchange(
            $exchange2 = $this->exchange()
        );

        $this->exchangeManager->bindExchange(
            new ExchangeBinding(
                $exchange2->name(),
                $exchange->name(),
                ['#']
            )
        );

        $this->assertTrue(true, sprintf('Could not declare the exchange "%s"', $exchange->name()));
    }

    /**
     * @test
     */
    public function itDeletesExchange()
    {
        $this->exchangeManager->declareExchange(
            $exchange = $this->exchange()
        );
        $this->exchangeManager->declareExchange(
            $exchange2 = $this->exchange()
        );

        $this->exchangeManager->deleteExchange($exchange->name());

        try {
            $this->exchangeManager->bindExchange(
                new ExchangeBinding(
                    $exchange2->name(),
                    $exchange->name(),
                    ['#']
                )
            );
        } catch (CannotBindExchangeException $e) {
            $this->assertTrue(true);
            return true;
        }

        $this->assertTrue(
            false,
            sprintf('Exchange "%s" still exists as it could be bound.', $exchange->name())
        );
    }

    /**
     * @test
     */
    public function itBindsExchangeToExchange()
    {
        list($sourceExchange, $destinationExchange) = $this->exchangeBinding('test.*');

        // bind all messages from destinationExchange
        $queueManager = $this->queueManager();
        $queueManager->declareQueue($queue = $this->queue());
        $queueManager->bindQueue(new QueueBinding($queue->name(), $destinationExchange->name(), ['#']));

        // queue should receive this message
        $this->exchangeManager->publishMessage(
            $publishedMessage = $this->randomAmqpMessage(),
            $sourceExchange->name(),
            'test.type_1'
        );

        $readMessage = $queueManager->readMessage($queue->name());
        $this->assertNotNull($readMessage);
        $this->assertEquals($publishedMessage->body, $readMessage->body);

        // queue should not receive this message
        $this->exchangeManager->publishMessage(
            $publishedMessage = $this->randomAmqpMessage(),
            $sourceExchange->name(),
            'not-test.type_1'
        );

        $readMessage = $queueManager->readMessage($queue->name());
        $this->assertNull($readMessage);
    }

    /**
     * @test
     */
    public function itUnbindsExchangeFromExchange()
    {
        list($sourceExchange, $destinationExchange, $binding) = $this->exchangeBinding('test.*');

        // bind all messages from destinationExchange
        $queueManager = $this->queueManager();
        $queueManager->declareQueue($queue = $this->queue());
        $queueManager->bindQueue(new QueueBinding($queue->name(), $destinationExchange->name(), ['#']));

        $this->exchangeManager->unbindExchange($binding);

        // queue should not receive this message
        $this->exchangeManager->publishMessage(
            $publishedMessage = $this->randomAmqpMessage(),
            $sourceExchange->name(),
            'test.type_1'
        );

        $readMessage = $queueManager->readMessage($queue->name());
        $this->assertNull($readMessage);
    }

    /**
     * @test
     */
    public function isPublishesMessage()
    {
        $this->exchangeManager->declareExchange($exchange = $this->exchange());

        // bind all messages from exchange
        $queueManager = $this->queueManager();
        $queueManager->declareQueue($queue = $this->queue());
        $queueManager->bindQueue(new QueueBinding($queue->name(), $exchange->name(), ['#']));

        $this->exchangeManager->publishMessage($publishedMessage = $this->randomAmqpMessage(), $exchange->name(), '');

        $queueManager = $this->queueManager();

        $readMessage = $queueManager->readMessage($queue->name());

        $this->assertNotNull($readMessage);
        $this->assertEquals($publishedMessage->body, $readMessage->body);
    }

    /**
     * @param string $routingKey
     * @return array
     */
    private function exchangeBinding(string $routingKey = '#')
    {
        $this->exchangeManager->declareExchange(
            $sourceExchange = $this->exchange()
        );
        $this->exchangeManager->declareExchange(
            $destinationExchange = $this->exchange()
        );

        $this->exchangeManager->bindExchange(
            $binding = new ExchangeBinding(
                $destinationExchange->name(),
                $sourceExchange->name(),
                [$routingKey]
            )
        );

        return [$sourceExchange, $destinationExchange, $binding];
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Integration\Util\Exchange;

use Webit\MessageBus\Infrastructure\Amqp\Channel\NewChannelConnectionAwareChannelFactory;
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
                $this->rabbitMqConnection()
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
                ['test.*']
            )
        );

        // bind all messages from exchange2
        $queueManager = $this->queueManager();
        $queueManager->declareQueue($queue = $this->queue());
        $queueManager->bindQueue(new QueueBinding($queue->name(), $exchange2->name(), ['#']));

        // queue should receive this message
        $this->exchangeManager->publishMessage(
            $publishedMessage = $this->randomAmqpMessage(),
            $exchange->name(),
            'test.type_1'
        );

        $readMessage = $queueManager->readMessage($queue->name());
        $this->assertEquals($publishedMessage->body, $readMessage->body);

        // queue should not receive this message
        $this->exchangeManager->publishMessage(
            $publishedMessage = $this->randomAmqpMessage(),
            $exchange->name(),
            'not-test.type_1'
        );

        $readMessage = $queueManager->readMessage($queue->name());
        $this->assertNull($readMessage);
    }
}

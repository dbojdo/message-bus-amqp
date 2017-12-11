<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Integration\Util\Exchange\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\ConnectionPool;
use Webit\MessageBus\Infrastructure\Amqp\Connection\Pool\Registry\ConnectionPoolRegistry;
use Webit\MessageBus\Infrastructure\Amqp\Registry\StaticRegistry;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Command\DeclareExchangeCommand;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeType;

class DeclareExchangeCommandTest extends AbstractCommandTestCase
{
    /** @var ConnectionPool */
    private $connectionPool;

    /** @var ConnectionPoolRegistry */
    private $registry;

    protected function setUp()
    {
        $this->connectionPool = $this->connectionPool();
        $this->registry = new StaticRegistry(['connection1' => $this->connectionPool]);
    }

    /**
     * @test
     */
    public function itCreatesExchange()
    {
        $application = $this->application(new DeclareExchangeCommand($this->registry));
        try {
            $application->run(
                $input = new ArrayInput(
                    [
                        'command' => 'exchange:declare',
                        'connectionPool' => 'connection1',
                        'exchangeName' => $exchangeName = $this->randomString(),
                        'exchangeType' => $type = (string)ExchangeType::topic(),
                    ]
                ),
                $output = new BufferedOutput()
            );
        } catch (\Exception $e) {
//            var_dump($output->fetch());
            die('ble');
        }
    }
}
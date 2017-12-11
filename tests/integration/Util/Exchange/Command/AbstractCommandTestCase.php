<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Integration\Util\Exchange\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Webit\MessageBus\Infrastructure\Amqp\Integration\AbstractIntegrationTestCase;

abstract class AbstractCommandTestCase extends AbstractIntegrationTestCase
{
    /**
     * @param Command $command
     * @return Application
     */
    protected function application(Command $command)
    {
        $application = new Application('AMQP Util App', '1.0');
        $application->add($command);

        return $application;
    }
}

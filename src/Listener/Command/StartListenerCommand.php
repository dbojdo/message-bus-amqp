<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Listener\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webit\MessageBus\Infrastructure\Amqp\Listener\Registry\ListenerRegistry;

final class StartListenerCommand extends Command
{
    /** @var ListenerRegistry */
    private $registry;

    /**
     * AbstractExchangeCommand constructor.
     * @param ListenerRegistry $registry
     */
    public function __construct(ListenerRegistry $registry)
    {
        parent::__construct();
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this->setName('listener:start');
        $this->addArgument('listener', InputArgument::REQUIRED, 'Listener name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listener = $this->registry->listener($listenerName = $input->getArgument('listener'));
        $output->writeln(
            sprintf(
                'Listening for messages with <info>%s</info> listener...',
                $listenerName
            )
        );

        $listener->listen();
    }
}

<?php

namespace Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Command;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\Exchange;
use Webit\MessageBus\Infrastructure\Amqp\Util\Exchange\ExchangeType;

class DeclareExchangeCommand extends AbstractExchangeCommand
{
    protected function configure()
    {
        $this->setName('exchange:declare');
        $this->addArgument('connectionPool', InputArgument::REQUIRED, 'Connection Pool name');
        $this->addArgument('exchangeName', InputArgument::REQUIRED, 'Exchange name');
        $this->addArgument('exchangeType', InputArgument::REQUIRED, 'Exchange type (fanout|topic|headers|direct)');
        $this->addArgument('passive', InputArgument::OPTIONAL, 'Should exchange be passive (1|0)', '0');
        $this->addArgument('durable', InputArgument::OPTIONAL, 'Should exchange be durable (1|0)', '0');
        $this->addArgument('autoDelete', InputArgument::OPTIONAL, 'Should exchange be auto-deleted (1|0)', '1');
        $this->addArgument('internal', InputArgument::OPTIONAL, 'Should exchange be internal (1|0)', '0');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $exchange = $this->exchangeFromInput($input);
        $output->writeln(
            sprintf(
                'Declaring exchange<info>%s</info> (type: <info>%s</info>, passive: <info>%d</info>, durable: <info>%d<info>, auto-delete: <info>%d</info>, internal: <info>%d</info>)...',
                $exchange->name(),
                (string)$exchange->type(),
                $exchange->isPassive(),
                $exchange->isDurable(),
                $exchange->isAutoDelete(),
                $exchange->isInternal()
            )
        );

        $exchangeManager = $this->exchangeManager($input->getArgument('connectionPool'));
        $exchangeManager->declareExchange($exchange);
        $output->writeln('Exchange declaration has been <info>successful</info>.');
    }

    /**
     * @param InputInterface $input
     * @return Exchange
     */
    private function exchangeFromInput(InputInterface $input)
    {
        return new Exchange(
            $input->getArgument('exchangeName'),
            ExchangeType::fromString($input->getArgument('exchangeType')),
            $input->getArgument('passive') == '1',
            $input->getArgument('durable') == '1',
            $input->getArgument('autoDelete') == '1',
            $input->getArgument('internal') == '1'
        );
    }
}
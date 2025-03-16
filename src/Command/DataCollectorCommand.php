<?php

namespace App\Command;

use App\Service\DataCollectorService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:collect_currency_data', description: 'Collect currency data')]
class DataCollectorCommand extends Command
{
    private DataCollectorService $dataCollectorService;

    public function __construct(private LoggerInterface $logger, DataCollectorService $dataCollectorService) {
        // you *must* call the parent constructor
        parent::__construct();
        $this->dataCollectorService = $dataCollectorService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->dataCollectorService->fetchAndSaveFreshCurrencyRateData();

        return Command::SUCCESS;
    }
}
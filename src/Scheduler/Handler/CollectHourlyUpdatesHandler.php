<?php

namespace App\Scheduler\Handler;

use \App\Scheduler\Message\CollectHourlyUpdatesMessage;
use App\Service\DataCollectorService;
use App\Service\ExtRateApiInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class CollectHourlyUpdatesHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private LoggerInterface $logger,
        private ExtRateApiInterface $extRateApi
    ) {
        // ...
    }

    #[AsMessageHandler]
    public function __invoke(CollectHourlyUpdatesMessage $message)
    {
        $dataCollectorService = new DataCollectorService($this->entityManager, $this->logger, $this->extRateApi);
        $dataCollectorService->fetchAndSaveFreshCurrencyRateData();
    }
}
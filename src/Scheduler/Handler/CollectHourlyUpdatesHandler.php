<?php

namespace App\Scheduler\Handler;

use App\Scheduler\Message\CollectHourlyUpdatesMessage;
use App\Service\DataCollectorService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class CollectHourlyUpdatesHandler
{
    public function __construct(
        private DataCollectorService $dataCollectorService
    ) {
        // ...
    }

    #[AsMessageHandler]
    public function __invoke(CollectHourlyUpdatesMessage $message): void
    {
        $this->dataCollectorService->fetchAndSaveFreshCurrencyRateData();
    }
}

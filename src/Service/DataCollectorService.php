<?php

namespace App\Service;

use App\Entity\CurrencyPair;
use App\Entity\CurrencyRateData;
use Doctrine\ORM\EntityManagerInterface;

class DataCollectorService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ExtRateApiInterface $extRateApi
    ) {
        // ...
    }

    private function getCurrentRate(CurrencyPair $currencyPair): ?string
    {
        return $this->extRateApi->getCurrentCoinRate($currencyPair);
    }

    private function addCurrencyRateDataPoint(CurrencyPair $currencyPair, string $val): void
    {
        $timeSeriesData = new CurrencyRateData($currencyPair, $val);
        $this->entityManager->persist($timeSeriesData);
        $this->entityManager->flush();
    }

    public function fetchAndSaveFreshCurrencyRateData(): void
    {
        $currencyPairs = $this->entityManager->getRepository(CurrencyPair::class)->findAll();
        foreach ($currencyPairs as $currencyPair) {
            $rate = $this->getCurrentRate($currencyPair);
            if (!is_null($rate)) {
                $this->addCurrencyRateDataPoint($currencyPair, $rate);
            }
        }
    }
}

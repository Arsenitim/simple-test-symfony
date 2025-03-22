<?php

namespace App\Service;

use App\Entity\CurrencyPair;
use App\Entity\CurrencyRateData;
use Doctrine\ORM\EntityManagerInterface;

class DataProviderService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getCurrencyDataArray(
        CurrencyPair $currencyPair,
        \DateTimeInterface|null $dateTimeFrom,
        \DateTimeInterface|null $dateTimeTo
    ): mixed {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder->select('crd')
            ->from(CurrencyRateData::class, 'crd')
            ->where('crd.currencyPair = :currencyPair')
            ->setParameter('currencyPair', $currencyPair);

        if (!is_null($dateTimeFrom)) {
            $queryBuilder->andWhere('crd.timestamp >= :dateTimeFrom')
                ->setParameter('dateTimeFrom', $dateTimeFrom);
        }

        if (!is_null($dateTimeTo)) {
            $queryBuilder->andWhere('crd.timestamp <= :dateTimeTo')
                ->setParameter('dateTimeTo', $dateTimeTo);
        }

        return $queryBuilder->getQuery()->getResult();
    }


    public function getChartData(
        string $currencyBase,
        string $currencyQuote,
        \DateTimeInterface|null $dateTimeFrom,
        \DateTimeInterface|null $dateTimeTo
    ): ?array {
        $currencyPair =  $this->entityManager->getRepository(CurrencyPair::class)->findOneBy([
            'currencyBase' => $currencyBase,
            'currencyQuote' => $currencyQuote
        ]);

        if (!$currencyPair) {
            return null;
        }

        $currencyRateDataArray = $this->getCurrencyDataArray($currencyPair, $dateTimeFrom, $dateTimeTo);

        $timelineData = [];
        foreach ($currencyRateDataArray as $currencyRateData) {
            $timelineData[] = [
                'dateTime' => $currencyRateData->getTimestamp(),
                'value' => $currencyRateData->getValue()
            ];
        }

        return [
            'message' => 'here is some data for you',
            'currencyPair' => [
                'baseCurrency' => $currencyPair->getCurrencyBase(),
                'quoteCurrency' => $currencyPair->getCurrencyQuote()
            ],
            'timelineData' => $timelineData
        ];
    }

    public function listCurrencyPairs(): array
    {
        $currencyPairs =  $this->entityManager->getRepository(CurrencyPair::class)->findAll();
        $currencyPairsArray = [];
        foreach ($currencyPairs as $currencyPair) {
            $currencyPairsArray[] = [
                'id' => $currencyPair->getId(),
                'base' => $currencyPair->getCurrencyBase(),
                'quote' => $currencyPair->getCurrencyQuote()
            ];
        }

        return $currencyPairsArray;
    }
}

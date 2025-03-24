<?php

namespace App\Service;

use App\Entity\CurrencyPair;
use App\Entity\CurrencyRateData;
use App\Exception\DataNotFoundException;
use App\Exception\InputParametersIncorrectException;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeInterface;
use DateTime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class DataProviderService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function getCurrencyDataArray(
        CurrencyPair $currencyPair,
        DateTimeInterface|null $dateTimeFrom,
        DateTimeInterface|null $dateTimeTo
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

    /**
     * @return array<mixed>
     */
    public function getChartData(
        string $currencyBase,
        string $currencyQuote,
        ?string $beginDateTimeStr,
        ?string $endDateTimeStr
    ): array {
        $currencyPair =  $this->entityManager->getRepository(CurrencyPair::class)->findOneByBaseAndQuote(
            $currencyBase,
            $currencyQuote
        );

        if (empty($currencyPair)) {
            throw new DataNotFoundException('Currency rate pair not found');
        }

        try {
            $beginDateTime = $beginDateTimeStr ? new DateTime($beginDateTimeStr) : null;
            $endDateTime = $endDateTimeStr ? new DateTime($endDateTimeStr) : null;
        } catch (Exception $e) {
            // ToDo: Do not use HTTP exception here! Replace with a more general "Bad Params" exception!
            throw new InputParametersIncorrectException(
                'Invalid date format. Use "YYYY-MM-DD" or "YYYY-MM-DD HH:MM:SS".'
            );
        }

        $currencyRateDataArray = $this->getCurrencyDataArray($currencyPair, $beginDateTime, $endDateTime);

        if (empty($currencyRateDataArray)) {
            // ToDo: Do not use HTTP exception here! Replace with a more general "Not Found" exception!
            throw new NotFoundHttpException('error: no data for this rate pair');
        }

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

    /**
     * @return array<mixed>
     */
    public function listCurrencyPairs(): array
    {
        $currencyPairs =  $this->entityManager->getRepository(CurrencyPair::class)->findAll();
        $currencyPairsArray = [];
        foreach ($currencyPairs as $currencyPair) {
            $currencyPairsArray[] = [
                'base' => $currencyPair->getCurrencyBase(),
                'quote' => $currencyPair->getCurrencyQuote()
            ];
        }

        if (empty($currencyPairsArray)) {
            throw new DataNotFoundException('Currency rate pairs not found');
        }
        return $currencyPairsArray;
    }
}

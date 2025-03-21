<?php

namespace App\Controller;

use App\Service\DataProviderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

class ApiController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/')]
    #[Route('/api/')]
    public function index(): Response
    {
        return new Response(
            'habahaba'
        );
    }

    #[Route('/api/currency_pairs', name: 'api_currency_pairs', methods: ['GET'])]
    public function getCurrencyPairs(): JsonResponse
    {
        $dataProvider = new DataProviderService($this->entityManager);
        $currencyPairsArray = $dataProvider->listCurrencyPairs();
        return $this->json($currencyPairsArray);
    }

    #[Route('/api/chart_data', name: 'api_chart_data', methods: ['GET'])]
    public function getChartData(
        #[MapQueryParameter] string $currencyBase,
        #[MapQueryParameter] string $currencyQuote,
        #[MapQueryParameter] ?string $beginDateTimeStr,
        #[MapQueryParameter] ?string $endDateTimeStr
    ): JsonResponse {
        try {
            $beginDateTime = $beginDateTimeStr ? new \DateTime($beginDateTimeStr) : null;
            $endDateTime = $endDateTimeStr ? new \DateTime($endDateTimeStr) : null;
        } catch (\Exception $e) {
            throw new BadRequestHttpException('Invalid date format. Use "YYYY-MM-DD" or "YYYY-MM-DD HH:MM:SS".');
        }

        $dataProvider = new DataProviderService($this->entityManager);
        $data = $dataProvider->getChartData($currencyBase, $currencyQuote, $beginDateTime, $endDateTime);
        if (is_null($data)) {
            throw new NotFoundHttpException(
                'error: no data for this rate. ' .
                'Please use /api/currency_pairs to get the list of possible rates'
            );
        }
        return $this->json($data);
    }
}

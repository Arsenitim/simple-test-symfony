<?php

namespace App\Controller;

use App\Exception\DataNotFoundException;
use App\Exception\InputParametersIncorrectException;
use App\Service\DataProviderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;

define(
    "DATETIME_QUERY_PARAM_REGEXP",
    '/^(19|20)\d\d-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])(?:\s(0\d|1\d|2[0-3]):([0-5]\d):([0-5]\d))?$/'
);
define("CURRENCY_CODE_QUERY_PARAM_REGEXP", '/^[a-z0-9]+(-[a-z0-9]+)*$/');

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
            'nothing here...'
        );
    }

    #[Route('/api/currency_pairs', name: 'api_currency_pairs', methods: ['GET'])]
    public function getCurrencyPairs(): JsonResponse
    {
        $dataProvider = new DataProviderService($this->entityManager);
        try {
            $currencyPairsArray = $dataProvider->listCurrencyPairs();
        } catch (DataNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $this->json($currencyPairsArray);
    }

    #[Route('/api/chart_data', name: 'api_chart_data', methods: ['GET'])]
    public function getChartData(
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => CURRENCY_CODE_QUERY_PARAM_REGEXP])]
        string $currencyBase,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => CURRENCY_CODE_QUERY_PARAM_REGEXP])]
        string $currencyQuote,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => DATETIME_QUERY_PARAM_REGEXP])]
        ?string $beginDateTimeStr,
        #[MapQueryParameter(filter: \FILTER_VALIDATE_REGEXP, options: ['regexp' => DATETIME_QUERY_PARAM_REGEXP])]
        ?string $endDateTimeStr
    ): JsonResponse {
        $dataProvider = new DataProviderService($this->entityManager);

        try {
            $data = $dataProvider->getChartData($currencyBase, $currencyQuote, $beginDateTimeStr, $endDateTimeStr);
        } catch (InputParametersIncorrectException $e) {
            throw new BadRequestHttpException($e->getMessage());
        } catch (DataNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return $this->json($data);
    }
}

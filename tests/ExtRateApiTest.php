<?php

use App\Service\ExtRateApiInterface;
use App\Service\CoingeckoRateApiService;
use App\Entity\CurrencyPair;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class ExtRateApiTest extends KernelTestCase
{

    public function testInterfaceImplementation(): void
    {
        $httpClientMock = $this->createMock(HttpClientInterface::class);

        $extRateApiInterface = new CoingeckoRateApiService($httpClientMock);

        $this->assertInstanceOf(ExtRateApiInterface::class, $extRateApiInterface);
    }

    public function testFetchingRateData(): void
    {
        // Instantiate a real HttpClientInterface
        $httpClient = self::getContainer()->get(HttpClientInterface::class);

        $extRateApiInterface = new CoingeckoRateApiService($httpClient);

        $currencyPair = new CurrencyPair();
        $currencyPair->setCurrencyBase('bitcoin');
        $currencyPair->setCurrencyQuote('usd');

        $returnedValue = $extRateApiInterface->getCurrentCoinRate($currencyPair);

        // Make sure it's a string...
        $this->assertIsString($returnedValue);

        // It should be a string with just numbers and a decimal point somewhere in between
        $this->assertMatchesRegularExpression('/^\d+\.\d+$/', $returnedValue);
    }
}
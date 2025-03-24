<?php

namespace App\Tests;

use App\Constants\ServiceConstants;
use App\Entity\CurrencyPair;
use App\Service\CoingeckoRateApiService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BasicTest extends KernelTestCase
{
    private function getTestCoingeckoUrl(): string
    {
        // Will just return a static URL for noц
        return sprintf(
            '%s?ids=%s&vs_currencies=%s&precision=%s',
            ServiceConstants::COINGECKO_API_URL,
            'bitcoin',
            'usd',
            ServiceConstants::COINGECKO_PRECISION
        );
    }
    public function testFetchingThrowsErrorOnBadData(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')->willReturn([
            'bitcoin' => ['usd' => "a_bad_string"]
        ]);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);

        $mockHttpClient->method('request')
            ->with('GET', $this->getTestCoingeckoUrl())
            ->willReturn($mockResponse);


        $extRateApiInterface = new CoingeckoRateApiService($mockHttpClient);

        $currencyPair = new CurrencyPair();
        $currencyPair->setCurrencyBase('bitcoin');
        $currencyPair->setCurrencyQuote('usd');

        $this->expectException(Exception::class);

        $extRateApiInterface->getCurrentCoinRate($currencyPair);
    }

    public function testFetchingRateDataFromMockApi(): void
    {
        $mockResponse = $this->createMock(ResponseInterface::class);
        $mockResponse->method('toArray')->willReturn([
            'bitcoin' => ['usd' => "123.456"]
        ]);

        $mockHttpClient = $this->createMock(HttpClientInterface::class);

        $mockHttpClient->method('request')
            ->with('GET', $this->getTestCoingeckoUrl())
            ->willReturn($mockResponse);

        $extRateApiInterface = new CoingeckoRateApiService($mockHttpClient);

        $currencyPair = new CurrencyPair();
        $currencyPair->setCurrencyBase('bitcoin');
        $currencyPair->setCurrencyQuote('usd');

        $returnedValue = $extRateApiInterface->getCurrentCoinRate($currencyPair);

        // It should be a string with just numbers and a decimal point somewhere in between
        $this->assertMatchesRegularExpression('/^\d+\.\d+$/', $returnedValue);
    }


    public function testFetchingRateDataFromRealApi(): void
    {
        // Instantiate a real HttpClientInterface
        $httpClient = self::getContainer()->get(HttpClientInterface::class);

        $extRateApiInterface = new CoingeckoRateApiService($httpClient);

        $currencyPair = new CurrencyPair();
        $currencyPair->setCurrencyBase('bitcoin');
        $currencyPair->setCurrencyQuote('usd');

        $returnedValue = $extRateApiInterface->getCurrentCoinRate($currencyPair);

        // It should be a string with just numbers and a decimal point somewhere in between
        $this->assertMatchesRegularExpression('/^\d+\.\d+$/', $returnedValue);
    }
}

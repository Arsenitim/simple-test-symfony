<?php

namespace App\Service;

use App\Entity\CurrencyPair;
use App\Service\ExtRateApiInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CoingeckoRateApiService implements ExtRateApiInterface
{
    private HttpClientInterface $httpClient;
    private string $apiUrl = 'https://api.coingecko.com/api/v3/simple/price';

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getCurrentCoinRate(CurrencyPair $currencyPair): string
    {
        $coin = $currencyPair->getCurrencyBase();
        $currency = $currencyPair->getCurrencyQuote();

        $url = sprintf(
            '%s?ids=%s&vs_currencies=%s&precision=%s',
            ServiceConstants::COINGECKO_API_URL,
            $coin,
            $currency,
            ServiceConstants::COINGECKO_PRECISION
        );


        $response = $this->httpClient->request('GET', $url);
        $data = $response->toArray();
        $rateValue = $data[$coin][$currency];

        if (!preg_match('/^\d+\.\d+$/', $rateValue)) {
            throw new Exception("Something is wrong with Coingecko Rate Api. Value received: " . $rateValue);
        }

        return strval($rateValue);
    }
}

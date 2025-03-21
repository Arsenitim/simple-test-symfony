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

    public function getCurrentCoinRate(CurrencyPair $currencyPair): ?string
    {
        $coin = $currencyPair->getCurrencyBase();
        $currency = $currencyPair->getCurrencyQuote();
        $precision = 16;

        $url = sprintf('%s?ids=%s&vs_currencies=%s&precision=%s', $this->apiUrl, $coin, $currency, $precision);

        try {
            $response = $this->httpClient->request('GET', $url);
            $data = $response->toArray();

            return strval($data[$coin][$currency]) ?? null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

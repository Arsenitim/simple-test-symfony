<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTest extends WebTestCase
{
    public function testApiRootUrl(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/');

        $this->assertResponseIsSuccessful();
    }

    public function testListCurrencyPairsResponseCode(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/currency_pairs');

        $this->assertResponseIsSuccessful();
    }

    public function testChartDataNoParams(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/chart_data');

        $this->assertResponseStatusCodeSame(404); // Should probably make it fire 400?
    }
    // ToDo: More to add!!!
}

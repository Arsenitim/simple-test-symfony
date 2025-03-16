<?php

namespace App\DataFixtures;

use App\Entity\CurrencyPair;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $entityManager): void
    {
        $data = [
            ['currency_base' => 'bitcoin', 'currency_quote' => 'aud'],
            ['currency_base' => 'bitcoin', 'currency_quote' => 'usd'],
            ['currency_base' => 'tether', 'currency_quote' => 'eur'],
            ['currency_base' => 'solana', 'currency_quote' => 'cad'],
            ['currency_base' => 'tether', 'currency_quote' => 'usd']
        ];

        foreach ($data as $row) {
            $currencyPair = new CurrencyPair();
            $currencyPair->setCurrencyBase($row['currency_base']);
            $currencyPair->setCurrencyQuote($row['currency_quote']);


            $entityManager->persist($currencyPair);
        }

        $entityManager->flush();
    }
}

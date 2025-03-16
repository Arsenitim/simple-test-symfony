<?php

namespace App\Service;

use App\Entity\CurrencyPair;

interface ExtRateApiInterface
{
    public function getCurrentCoinRate(CurrencyPair $currencyPair);
}
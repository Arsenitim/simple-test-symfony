<?php

namespace App\Entity;

use App\Repository\CurrencyPairRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyPairRepository::class)]
class CurrencyPair
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 32)]
    private string $currencyBase;

    #[ORM\Column(length: 32)]
    private string $currencyQuote;

    #[ORM\OneToMany(targetEntity: CurrencyRateData::class, mappedBy: "currencyPair", cascade: ["persist", "remove"])]
    private Collection $currencyRateData;

    public function __construct()
    {
        $this->currencyRateData = new ArrayCollection();
    }

    public function getCurrencyBase(): string
    {
        return $this->currencyBase;
    }

    public function setCurrencyBase(string $currencyBase): static
    {
        $this->currencyBase = $currencyBase;
        return $this;
    }

    public function getCurrencyQuote(): string
    {
        return $this->currencyQuote;
    }

    public function setCurrencyQuote(string $currencyQuote): static
    {
        $this->currencyQuote = $currencyQuote;
        return $this;
    }
}

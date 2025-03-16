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
    private ?int $id = null;

    #[ORM\Column(length: 32)]
    private ?string $currencyBase = null;

    #[ORM\Column(length: 32)]
    private ?string $currencyQuote = null;

    public function getCurrencyRateData(): Collection
    {
        return $this->currencyRateData;
    }

    public function setCurrencyRateData(Collection $currencyRateData): void
    {
        $this->currencyRateData = $currencyRateData;
    }

    // Relations
    #[ORM\OneToMany(targetEntity: CurrencyRateData::class, mappedBy: "currencyPair", cascade: ["persist", "remove"])]
    private Collection $currencyRateData;

    public function __construct()
    {
        $this->currencyRateData = new ArrayCollection();
    }

    public function addCurrencyRateData(CurrencyRateData $currencyRateData): CurrencyPair
    {
        $currencyRateData->setCurrencyPair($this);

        $this->currencyRateData->add($currencyRateData);

        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyBase(): ?string
    {
        return $this->currencyBase;
    }

    public function setCurrencyBase(string $currencyBase): static
    {
        $this->currencyBase = $currencyBase;

        return $this;
    }

    public function getCurrencyQuote(): ?string
    {
        return $this->currencyQuote;
    }

    public function setCurrencyQuote(string $currencyQuote): static
    {
        $this->currencyQuote = $currencyQuote;

        return $this;
    }
}

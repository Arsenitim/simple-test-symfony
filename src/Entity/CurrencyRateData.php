<?php

namespace App\Entity;

use App\Repository\CurrencyRateDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRateDataRepository::class)]
class CurrencyRateData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $currencyPairId = null;



    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeInterface $timestamp = null;

    #[ORM\Column(length: 255)]
    private ?string $value = null;


    #[ORM\ManyToOne(targetEntity: CurrencyPair::class, inversedBy: "currencyRateData")]
    #[ORM\JoinColumn(nullable: false)]
    private CurrencyPair $currencyPair;

    public function __construct(CurrencyPair $currencyPair, string $value)
    {
        $this->currencyPairId = $currencyPair->getId();
        $this->currencyPair = $currencyPair;
        $this->timestamp = new \DateTime(); // Default to now
        $this->value = $value;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrencyPairId(): ?int
    {
        return $this->currencyPairId;
    }

    public function setCurrencyPairId(?int $currencyPairId): void
    {
        $this->currencyPairId = $currencyPairId;
    }

    public function getCurrencyPair(): CurrencyPair
    {
        return $this->currencyPair;
    }

    public function setCurrencyPair(CurrencyPair $currencyPair): static
    {
        $this->currencyPair = $currencyPair;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): static
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }
}

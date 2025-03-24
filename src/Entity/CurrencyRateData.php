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
    private int $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeInterface $timestamp;

    #[ORM\Column(length: 255)]
    private string $value;


    #[ORM\ManyToOne(targetEntity: CurrencyPair::class, inversedBy: "currencyRateData")]
    #[ORM\JoinColumn(nullable: false)]
    private CurrencyPair $currencyPair;

    public function __construct(CurrencyPair $currencyPair, string $value)
    {
        $this->currencyPair = $currencyPair;
        $this->timestamp = new \DateTime(); // Default to now
        $this->value = $value;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): DateTimeInterface
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

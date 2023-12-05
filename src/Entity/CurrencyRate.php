<?php

namespace App\Entity;

use App\Repository\CurrencyRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurrencyRateRepository::class)]
class CurrencyRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 366)]
    private ?string $currency1_name = null;

    #[ORM\Column(length: 366)]
    private ?string $currency2_name = null;

    #[ORM\Column]
    private ?float $rate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency1Name(): ?string
    {
        return $this->currency1_name;
    }

    public function setCurrency1Name(string $currency1_name): static
    {
        $this->currency1_name = $currency1_name;

        return $this;
    }

    public function getCurrency2Name(): ?string
    {
        return $this->currency2_name;
    }

    public function setCurrency2Name(string $currency2_name): static
    {
        $this->currency2_name = $currency2_name;

        return $this;
    }

    public function setString(string $string): static
    {
        $this->string = $string;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rate;
    }

    public function setRate(float $rate): static
    {
        $this->rate = $rate;

        return $this;
    }
}

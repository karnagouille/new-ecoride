<?php

namespace App\Entity;


use App\Repository\CarpoolingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarpoolingRepository::class)]
class Carpooling
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $startTown = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $endTown = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $startAt = null;

    #[ORM\ManyToOne(inversedBy: 'carpoolings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\Column]
    private ?int $passenger = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $hour = null;


    // ---------- Getters / Setters ----------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTown(): ?string
    {
        return $this->startTown;
    }

    public function setStartTown(string $startTown): static
    {
        $this->startTown = $startTown;
        return $this;
    }

    public function getEndTown(): ?string
    {
        return $this->endTown;
    }

    public function setEndTown(string $endTown): static
    {
        $this->endTown = $endTown;
        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): static
    {
        $this->startAt = $startAt;
        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;
        return $this;
    }

    public function getPassenger(): ?int
    {
        return $this->passenger;
    }

    public function setPassenger(int $passenger): static
    {
        $this->passenger = $passenger;

        return $this;
    }

    public function getHour(): ?\DateTime
    {
        return $this->hour;
    }

    public function setHour(\DateTime $hour): static
    {
        $this->hour = $hour;

        return $this;
    }


}

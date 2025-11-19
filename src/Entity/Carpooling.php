<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CarpoolingRepository;

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
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'carpoolings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\Column( nullable: true)]
    private ?int $passenger = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTime $hour = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $traveltime = null;

    #[ORM\Column(nullable: true)]
    private ?bool $electric = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $note = null;


    public const STATUT_RIEN = 'rien';
    public const STATUT_EN_COURS = 'en_cours';
    public const STATUT_TERMINE = 'termine';
    public const STATUT_ANNULEE = 'annulee';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = self::STATUT_RIEN;

    /**
     * @var Collection<int, Participant>
    */
    #[ORM\OneToMany(targetEntity: Participant::class, mappedBy: 'carpooling')]
    private Collection $participants;

    /**
     * @var Collection<int, CreditTransaction>
     */
    #[ORM\OneToMany(targetEntity: CreditTransaction::class, mappedBy: 'carpooling')]
    private Collection $creditTransactions;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartTown(): ?string
    {
        return $this->startTown;
    }

    public function setStartTown(?string $startTown): static
    {
        $this->startTown = $startTown;
        return $this;
    }

    public function getEndTown(): ?string
    {
        return $this->endTown;
    }

    public function setEndTown(?string $endTown): static
    {
        $this->endTown = $endTown;
        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(?\DateTimeInterface $startAt): static
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

    public function setPassenger(?int $passenger): static
    {
        $this->passenger = $passenger;

        return $this;
    }

    public function getHour(): ?\DateTime
    {
        return $this->hour;
    }

    public function setHour(?\DateTime $hour): static
    {
        $this->hour = $hour;

        return $this;
    }


    public function getTraveltime(): ?string
    {
        return $this->traveltime;
    }

    public function setTraveltime(?string $traveltime): static
    {
        $this->traveltime = $traveltime;

        return $this;
    }

    public function getElectric(): ?bool
    {
        return $this->electric;
    }

    public function setElectric(?bool $electric): static
    {
        $this->electric = $electric;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setCarpooling($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            if ($participant->getCarpooling() === $this) {
                $participant->setCarpooling(null);
            }
        }
        return $this;
    }

    public function __construct()
{
    $this->participants = new ArrayCollection();
    $this->creditTransactions = new ArrayCollection();
}

    /**
     * @return Collection<int, CreditTransaction>
     */
    public function getCreditTransactions(): Collection
    {
        return $this->creditTransactions;
    }

    public function addCreditTransaction(CreditTransaction $creditTransaction): static
    {
        if (!$this->creditTransactions->contains($creditTransaction)) {
            $this->creditTransactions->add($creditTransaction);
            $creditTransaction->setCarpooling($this);
        }

        return $this;
    }

    public function removeCreditTransaction(CreditTransaction $creditTransaction): static
    {
        if ($this->creditTransactions->removeElement($creditTransaction)) {

            if ($creditTransaction->getCarpooling() === $this) {
                $creditTransaction->setCarpooling(null);
            }
        }

        return $this;
    }


}
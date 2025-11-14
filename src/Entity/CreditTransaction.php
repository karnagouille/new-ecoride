<?php

namespace App\Entity;

use App\Repository\CreditTransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditTransactionRepository::class)]
class CreditTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\ManyToOne(inversedBy: 'creditTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carpooling $carpooling = null;

    #[ORM\ManyToOne(inversedBy: 'sentTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'receivedTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receiver = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCarpooling(): ?Carpooling
    {
        return $this->carpooling;
    }

    public function setCarpooling(?Carpooling $carpooling): static
    {
        $this->carpooling = $carpooling;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): static
    {
        $this->receiver = $receiver;

        return $this;
    }

}

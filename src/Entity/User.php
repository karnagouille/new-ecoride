<?php

namespace App\Entity;

use App\Entity\Car;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'il y a déjà un compte avec ce pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phonenumber = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $date_birth = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\Column(type: 'json')]

    private array $roles = [];
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Carpooling::class)]
    private Collection $carpoolings;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Car::class)]
    private Collection $cars;

    #[ORM\Column]
    private bool $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Participant::class)]
private Collection $participants;

    /**
     * @var Collection<int, CreditTransaction>
     */
    #[ORM\OneToMany(targetEntity: CreditTransaction::class, mappedBy: 'sender')]
    private Collection $sentTransactions;

    /**
     * @var Collection<int, CreditTransaction>
     */
    #[ORM\OneToMany(targetEntity: CreditTransaction::class, mappedBy: 'receiver')]
    private Collection $receivedTransactions;

    #[ORM\Column]
    private ?float $platformCredit = 0;

    #[ORM\Column(nullable: false)]
    private ?float $credit = 20;

    #[ORM\Column]
    private ?bool $isActive = true;



    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }


    public function getUserIdentifier(): string
    {
        return $this->email ?? '';
    }


    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {

    }

    
    /**
     * @return Collection|Car[]
     */
    public function getCars(): Collection
    {
        return $this->cars;
    }

    public function addCar(Car $car): static
    {
        if (!$this->cars->contains($car)) {
            $this->cars->add($car);
            $car->setUser($this);
        }
        return $this;
    }

    public function removeCar(Car $car): static
    {
        if ($this->cars->removeElement($car)) {
            if ($car->getUser() === $this) {
                $car->setUser(null);
            }
        }
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getDateBirth(): ?string
    {
        return $this->date_birth;
    }

    public function setDateBirth(string $date_birth): static
    {
        $this->date_birth = $date_birth;
        return $this;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;
        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

public function getCarpoolings(): Collection
{
    return $this->carpoolings;
}

    public function __construct()
    {
        $this->cars = new ArrayCollection();
        $this->carpoolings = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->sentTransactions = new ArrayCollection();
        $this->receivedTransactions = new ArrayCollection(); 
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
            $participant->setUser($this);
        }
        return $this;
    }

    public function removeParticipant(Participant $participant): static
    {
        if ($this->participants->removeElement($participant)) {
            if ($participant->getUser() === $this) {
                $participant->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, CreditTransaction>
     */
    public function getSentTransactions(): Collection
    {
        return $this->sentTransactions;
    }

    public function addSentTransaction(CreditTransaction $sentTransaction): static
    {
        if (!$this->sentTransactions->contains($sentTransaction)) {
            $this->sentTransactions->add($sentTransaction);
            $sentTransaction->setSender($this);
        }

        return $this;
    }

    public function removeSentTransaction(CreditTransaction $sentTransaction): static
    {
        if ($this->sentTransactions->removeElement($sentTransaction)) {
            // set the owning side to null (unless already changed)
            if ($sentTransaction->getSender() === $this) {
                $sentTransaction->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CreditTransaction>
     */
    public function getReceivedTransactions(): Collection
    {
        return $this->receivedTransactions;
    }

    public function addReceivedTransaction(CreditTransaction $receivedTransaction): static
    {
        if (!$this->receivedTransactions->contains($receivedTransaction)) {
            $this->receivedTransactions->add($receivedTransaction);
            $receivedTransaction->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedTransaction(CreditTransaction $receivedTransaction): static
    {
        if ($this->receivedTransactions->removeElement($receivedTransaction)) {
            // set the owning side to null (unless already changed)
            if ($receivedTransaction->getReceiver() === $this) {
                $receivedTransaction->setReceiver(null);
            }
        }

        return $this;
    }

    public function getPlatformCredit(): ?float
    {
        return $this->platformCredit;
    }

    public function setPlatformCredit(float $platformCredit): static
    {
        $this->platformCredit = $platformCredit;

        return $this;
    }

    public function getCredit(): ?float
    {
        return $this->credit;
    }

    public function setCredit(?float $credit): static
    {
        $this->credit = $credit;

        return $this;
    }

   

}

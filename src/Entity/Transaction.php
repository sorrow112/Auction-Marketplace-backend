<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:transaction:collection'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:transaction:collection','write:transaction', 'read:commande:collection'])]
    private $transaction_id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:transaction:collection','write:transaction'])]
    private $date;

    #[ORM\Column(type: 'float')]
    #[Groups(['read:transaction:collection','write:transaction'])]
    #[Assert\Positive]
    private $montant;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'Payed')]
    #[Groups(['read:transaction:item','write:transaction'])]
    private $transmitter;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'GotPayed')]
    #[Groups(['read:transaction:item','write:transaction'])]
    private $transmittedTo;

    #[ORM\OneToOne(mappedBy: 'transaction', targetEntity: Commande::class, cascade: ['persist', 'remove'])]
    #[Groups(['read:transaction:item','write:transaction'])]
    private $commande;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionId(): ?string
    {
        return $this->transaction_id;
    }

    public function setTransactionId(string $transaction_id): self
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getTransmitter(): ?User
    {
        return $this->transmitter;
    }

    public function setTransmitter(?User $transmitter): self
    {
        $this->transmitter = $transmitter;

        return $this;
    }

    public function getTransmittedTo(): ?User
    {
        return $this->transmittedTo;
    }

    public function setTransmittedTo(?User $transmittedTo): self
    {
        $this->transmittedTo = $transmittedTo;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): self
    {
        // unset the owning side of the relation if necessary
        if ($commande === null && $this->commande !== null) {
            $this->commande->setTransaction(null);
        }

        // set the owning side of the relation if necessary
        if ($commande !== null && $commande->getTransaction() !== $this) {
            $commande->setTransaction($this);
        }

        $this->commande = $commande;

        return $this;
    }
}

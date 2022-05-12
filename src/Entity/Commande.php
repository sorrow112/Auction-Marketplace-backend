<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CommandeRepository;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]

class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:transaction:item', 'read:commande:collection'])]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['write:commande', 'read:commande:collection'])]
    private $date;

    #[ORM\OneToOne(targetEntity: Panier::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write:commande', 'read:commande:collection'])]
    private $panier;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write:commande', 'read:commande:collection'])]
    private $user;

    #[ORM\OneToOne(inversedBy: 'commande', targetEntity: Transaction::class, cascade: ['persist', 'remove'])]
    #[Groups(['write:commande', 'read:commande:collection'])]
    private $transaction;

    public function __construct()
    {
        $this->date = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(Panier $panier): self
    {
        $this->panier = $panier;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }
}

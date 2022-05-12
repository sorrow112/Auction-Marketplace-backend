<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PanierRepository;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PanierRepository::class)]

class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:panier:collection'])]
    private $id;

    #[ORM\Column(type: 'datetime')]
    #[Groups(['write:panier', 'read:panier:collection'])]
    private $date;

    #[ORM\ManyToMany(targetEntity: Vente::class)]
    #[Groups(['write:panier', 'read:panier:collection', 'read:commande:collection'])]
    private $ventes;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'paniers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['write:panier', 'read:panier:collection'])]
    private $user;

    public function __construct()
    {
        $this->ventes = new ArrayCollection();
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

    /**
     * @return Collection|Vente[]
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Vente $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes[] = $vente;
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        $this->ventes->removeElement($vente);

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
}

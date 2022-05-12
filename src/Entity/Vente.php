<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\VenteRepository;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VenteRepository::class)]
class Vente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:vente:collection'])]
    private $id;


    #[ORM\Column(type: 'integer')]
    #[Groups(['read:vente:collection', 'write:vente'])]
    #[Assert\GreaterThan(0)]
    private $quantity;

    #[ORM\Column(type: 'float')]
    #[Groups(['read:vente:collection', 'write:vente', 'read:panier:collection'])]
    #[Assert\Positive]
    private $price;

    #[ORM\OneToOne(inversedBy: 'vente', targetEntity: Article::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:vente:collection', 'write:vente'])]
    private $article;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'ventes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:vente:collection', 'write:vente'])]
    private $user;


    #[ORM\Column(type: 'datetime')]
    #[Groups('read:vente:item')]
    private $createdAt;

    #[ORM\Column(type: 'datetime')]
    private $updatedAt;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'ventes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:vente:item','write:vente'])]
    private $category;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();    

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(Article $article): self
    {
        $this->article = $article;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

}

<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:article:collection']],
    denormalizationContext: ['groups' => ['write:article']],
    itemOperations: [
        'put',
        'delete',
        'get' => [
            'normalisation_context' => ['groups' => ['read:article:collection', 'read:article:item']]
        ]
    ]
        ),ApiFilter(
    SearchFilter::class ,
    properties: ['name' => 'partial', 'id' => 'exact']
)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:vente:collection', 'read:enchere:collection', 'read:enchereInverse:collection', 'read:surveille:collection', 'read:panier:collection', 'read:fermeture:collection'])]
    private $id;
   

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:vente:collection', 'read:enchere:collection', 'read:enchereInverse:collection', 'read:surveille:collection', 'read:panier:collection'
    , 'read:fermeture:collection','write:article'])]
    #[Assert\Length(
        min: 3,
        max: 15,
        maxMessage: 'le nom de marque est trop long',
        minMessage: 'le nom de marque est trop court'
    )]
    private $name;


    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:vente:item', 'read:enchere:item', 'read:enchereInverse:item','write:article'])]
    #[Assert\Choice(['neuf', 'utilisé'])]
    private $state;


    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:vente:item', 'read:enchere:item', 'read:enchereInverse:item','write:article'])]
    private $localisation;


    #[ORM\Column(type: 'datetime')]
    #[Groups(['read:vente:item', 'read:enchere:item', 'read:enchereInverse:item','write:article'])]
    #[Assert\LessThan('today')]
    private $fabrication_date;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:vente:item', 'read:enchere:item', 'read:enchereInverse:item','write:article'])]
    #[Assert\Length(
        max: 15,
        maxMessage: 'le nom de marque est trop long',
    )]
    private $brand;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read:vente:item', 'read:enchere:item', 'read:enchereInverse:item','write:article'])]
    private $codebar;

    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Document::class)]
    #[Groups(['read:vente:item', 'read:enchere:item', 'read:enchereInverse:item','write:article'])]
    private $documents;


    #[ORM\OneToOne(mappedBy: 'article', targetEntity: Vente::class, cascade: ['persist', 'remove'])]
    private $vente;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['read:vente:item', 'read:enchere:item', 'read:enchereInverse:item'])]
    private $description;

    public function __construct()
    {
        $this->fabrication_date = new \DateTime();
        $this->documents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getFabricationDate(): ?\DateTimeInterface
    {
        return $this->fabrication_date;
    }

    public function setFabricationDate(\DateTimeInterface $fabrication_date): self
    {
        $this->fabrication_date = $fabrication_date;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getCodebar(): ?string
    {
        return $this->codebar;
    }

    public function setCodebar(?string $codebar): self
    {
        $this->codebar = $codebar;

        return $this;
    }

    /**
     * @return Collection|document[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setArticle($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getArticle() === $this) {
                $document->setArticle(null);
            }
        }

        return $this;
    }

    

    public function getVente(): ?Vente
    {
        return $this->vente;
    }

    public function setVente(Vente $vente): self
    {
        // set the owning side of the relation if necessary
        if ($vente->getArticle() !== $this) {
            $vente->setArticle($this);
        }

        $this->vente = $vente;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}

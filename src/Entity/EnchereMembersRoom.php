<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Repository\EnchereMembersRoomRepository;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: EnchereMembersRoomRepository::class)]
class EnchereMembersRoom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Groups(['read:room:item'])]
    private $capacity;

    #[ORM\Column(type: 'array', nullable: true)]
    #[Groups(['read:room:item'])]
    private $membersList = [];

    #[ORM\OneToOne(inversedBy: 'membersRoom', targetEntity: Enchere::class, cascade: ['persist', 'remove'])]
    #[Groups(['read:room:item'])]
    private $enchere;

    #[ORM\OneToOne(inversedBy: 'MembersRoom', targetEntity: EnchereInverse::class, cascade: ['persist', 'remove'])]
    #[Groups(['read:room:item'])]
    private $EnchereInverse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getMembersList(): ?array
    {
        return $this->membersList;
    }

    public function setMembersList(?array $membersList): self
    {
        $this->membersList = $membersList;

        return $this;
    }

    public function getEnchere(): ?Enchere
    {
        return $this->enchere;
    }

    public function setEnchere(?Enchere $enchere): self
    {
        $this->enchere = $enchere;

        return $this;
    }

    public function getEnchereInverse(): ?EnchereInverse
    {
        return $this->EnchereInverse;
    }

    public function setEnchereInverse(?EnchereInverse $EnchereInverse): self
    {
        $this->EnchereInverse = $EnchereInverse;

        return $this;
    }
}

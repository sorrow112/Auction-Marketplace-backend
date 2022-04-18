<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\Controller\RegisterController;
use App\Controller\UserDataController;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read:user:collection']],
    denormalizationContext: ['groups' => ['write:user']],
    paginationItemsPerPage:12 ,
    collectionOperations: [
        'search'=>[
            'path' => '/users/search',
            'method' => 'GET',
            "pagination_items_per_page" => 5,
            'normalisation_context' => ['groups' => ['read:users:search']]
        ],
        'get',
        'post'
    ],
    itemOperations: [
        'put' => ["security" => "is_granted('ROLE_ADMIN') or object == user"],
        'get' => [
            'normalisation_context' => ['groups' => ['read:user:collection', 'read:user:item']],
        ],
        'getData' =>[
            'paginationItemsPerPage'=> false,
            'path' => '/userdata',
            'method'=> 'get',
            'controller' => UserDataController::class,
            'read'=>false,
            ],
        'register'=>[
            'path' => '/register',
            'method'=>'post',
            'controller' => RegisterController::class,
            'normalization_context' => ['groups' => 'write:user'],
            'read' => false,
        ]
    ],
    
)]
#[ApiFilter(SearchFilter::class ,properties: ['displayName'=>'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['read:vente:item', 'read:vente:collection'
    , 'read:transaction:item', 'read:panier:collection',
     'read:fermeture:collection','read:user:collection',
     'read:enchere:collection','read:enchereInverse:collection',
     'write:enchere', 'read:proposition:collection'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['write:user', 'read:user:collection'
    ])]
    #[Assert\Regex(
        pattern: "/^[a-z ,.'-]+$/i",
        message: "le nom ne doit pas contenir des characteres speciaux et ça doit etre entre 2 et 5 mots"
    )]
    #[Assert\NotNull]
    private $name;

    #[ORM\Column(type: 'string', length: 20)]
    #[Groups(['read:surveille:collection','read:vente:collection', 'write:user', 
    'read:user:collection', 'read:transaction:item', 
    'read:fermeture:collection', 'read:enchere:collection',
    'read:enchereInverse:collection', 'write:enchere','read:users:search',
    'read:proposition:collection', 'read:demandeDevis:collection'])]
    #[Assert\Length(
        min: 5,
        max: 15,
        minMessage: "le nom d'utlisateur saisit est très court",
        maxMessage: "le nom d'utlisateur saisit est très long",
    )]
    #[Assert\Regex(
        pattern: "/^[A-Za-z][A-Za-z0-9]{5,31}$/",
        message: "le nom d'utilisateur ne doit pas contenir des characteres speciaux"
    )]
    #[Assert\NotNull]
    private $displayName;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Groups(['write:user', 'read:user:collection', 'write:enchere'])]
    #[Assert\Email(
        message: "l'email :{{ value }} n'est pas un email valide.",
    )]
    #[Assert\NotNull]
    private $email;

   
    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['write:user'])]
    #[SerializedName("password")]
    #[Assert\NotNull]
    private $password;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['write:user', 'read:user:collection'])]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'un numero de telephone a 8 chiffres',
        maxMessage: 'un numero de telephone a 8 chiffres',
    )]
    #[Assert\Regex(
        pattern: "/^[0-9]{8}$/",
        message: "votre numero de telephone doit contenir uniquement des chiffres"
    )]
    #[Assert\NotNull]
    private $telephone;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:vente:item', 'write:user', 'read:user:collection'])]
    private $avatar;

    #[ORM\Column(type: 'json')]
    #[Groups(['write:user', 'read:user:collection'])]
    private $roles = [];

    #[ORM\Column(type: 'boolean')]
    #[Groups(['write:user','read:user:collection'])]
    private $isActive;

    #[ORM\OneToMany(mappedBy: 'transmitter', targetEntity: DemandeDevis::class, orphanRemoval: true)]
    private $demandeDevisTransmis;

    #[ORM\OneToMany(mappedBy: 'transmitter', targetEntity: Proposition::class, orphanRemoval: true)]
    private $propositionsTransmises;

    #[ORM\OneToMany(mappedBy: 'transmittedTo', targetEntity: Proposition::class, orphanRemoval: true)]
    private $propositionsRecu;

    #[ORM\OneToMany(mappedBy: 'transmittedTo', targetEntity: DemandeDevis::class, orphanRemoval: true)]
    private $demandesRecus;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Adresse::class)]
    #[Groups(['read:user:collection', 'read:enchere:collection', 'read:enchereInverse:collection'])]
    private $adresse;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Surveille::class, orphanRemoval: true)]
    #[Groups(['read:user:collection'])]
    private $surveilles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reduction::class, orphanRemoval: true)]
    private $reductions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Augmentation::class, orphanRemoval: true)]
    private $augmentations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Vente::class)]
    private $ventes;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Panier::class)]
    private $paniers;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Commande::class)]
    private $commandes;

    #[ORM\OneToMany(mappedBy: 'transmitter', targetEntity: Transaction::class)]
    private $Payed;

    #[ORM\OneToMany(mappedBy: 'transmittedTo', targetEntity: Transaction::class)]
    private $GotPayed;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['write:user', 'read:user:collection'])]
    private $birthDate;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, orphanRemoval: true)]
    private $notifications;


    public function __construct()
    {
        $this->demandeDevisTransmis = new ArrayCollection();
        $this->propositionsTransmises = new ArrayCollection();
        $this->propositionsRecu = new ArrayCollection();
        $this->demandesRecus = new ArrayCollection();
        $this->adresse = new ArrayCollection();
        $this->surveilles = new ArrayCollection();
        $this->reductions = new ArrayCollection();
        $this->augmentations = new ArrayCollection();
        $this->ventes = new ArrayCollection();
        $this->paniers = new ArrayCollection();
        $this->commandes = new ArrayCollection();
        $this->Payed = new ArrayCollection();
        $this->GotPayed = new ArrayCollection();
        $this->isActive = true;
        $this->notifications = new ArrayCollection();
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

    public function getUserName(): ?string
    {
        return $this->email;
    }
    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }


    public function setDisplayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {

        $this->password = $password;

        return $this;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection|DemandeDevis[]
     */
    public function getDemandeDevisTransmis(): Collection
    {
        return $this->demandeDevisTransmis;
    }

    public function addDemandeDevisTransmi(DemandeDevis $demandeDevisTransmi): self
    {
        if (!$this->demandeDevisTransmis->contains($demandeDevisTransmi)) {
            $this->demandeDevisTransmis[] = $demandeDevisTransmi;
            $demandeDevisTransmi->setTransmitter($this);
        }

        return $this;
    }

    public function removeDemandeDevisTransmi(DemandeDevis $demandeDevisTransmi): self
    {
        if ($this->demandeDevisTransmis->removeElement($demandeDevisTransmi)) {
            // set the owning side to null (unless already changed)
            if ($demandeDevisTransmi->getTransmitter() === $this) {
                $demandeDevisTransmi->setTransmitter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Proposition[]
     */
    public function getPropositionsTransmises(): Collection
    {
        return $this->propositionsTransmises;
    }

    public function addPropositionsTransmise(Proposition $propositionsTransmise): self
    {
        if (!$this->propositionsTransmises->contains($propositionsTransmise)) {
            $this->propositionsTransmises[] = $propositionsTransmise;
            $propositionsTransmise->setTransmitter($this);
        }

        return $this;
    }

    public function removePropositionsTransmise(Proposition $propositionsTransmise): self
    {
        if ($this->propositionsTransmises->removeElement($propositionsTransmise)) {
            // set the owning side to null (unless already changed)
            if ($propositionsTransmise->getTransmitter() === $this) {
                $propositionsTransmise->setTransmitter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Proposition[]
     */
    public function getPropositionsRecu(): Collection
    {
        return $this->propositionsRecu;
    }

    public function addPropositionsRecu(Proposition $propositionsRecu): self
    {
        if (!$this->propositionsRecu->contains($propositionsRecu)) {
            $this->propositionsRecu[] = $propositionsRecu;
            $propositionsRecu->setTransmittedTo($this);
        }

        return $this;
    }

    public function removePropositionsRecu(Proposition $propositionsRecu): self
    {
        if ($this->propositionsRecu->removeElement($propositionsRecu)) {
            // set the owning side to null (unless already changed)
            if ($propositionsRecu->getTransmittedTo() === $this) {
                $propositionsRecu->setTransmittedTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DemandeDevis[]
     */
    public function getDemandesRecus(): Collection
    {
        return $this->demandesRecus;
    }

    public function addDemandesRecu(DemandeDevis $demandesRecu): self
    {
        if (!$this->demandesRecus->contains($demandesRecu)) {
            $this->demandesRecus[] = $demandesRecu;
            $demandesRecu->setTransmittedTo($this);
        }

        return $this;
    }

    public function removeDemandesRecu(DemandeDevis $demandesRecu): self
    {
        if ($this->demandesRecus->removeElement($demandesRecu)) {
            // set the owning side to null (unless already changed)
            if ($demandesRecu->getTransmittedTo() === $this) {
                $demandesRecu->setTransmittedTo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Adresse[]
     */
    public function getAdresse(): Collection
    {
        return $this->adresse;
    }

    public function addAdresse(Adresse $adresse): self
    {
        if (!$this->adresse->contains($adresse)) {
            $this->adresse[] = $adresse;
            $adresse->setUser($this);
        }

        return $this;
    }

    public function removeAdresse(Adresse $adresse): self
    {
        if ($this->adresse->removeElement($adresse)) {
            // set the owning side to null (unless already changed)
            if ($adresse->getUser() === $this) {
                $adresse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Surveille[]
     */
    public function getSurveilles(): Collection
    {
        return $this->surveilles;
    }

    public function addSurveille(Surveille $surveille): self
    {
        if (!$this->surveilles->contains($surveille)) {
            $this->surveilles[] = $surveille;
            $surveille->setUser($this);
        }

        return $this;
    }

    public function removeSurveille(Surveille $surveille): self
    {
        if ($this->surveilles->removeElement($surveille)) {
            // set the owning side to null (unless already changed)
            if ($surveille->getUser() === $this) {
                $surveille->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reduction[]
     */
    public function getReductions(): Collection
    {
        return $this->reductions;
    }

    public function addReduction(Reduction $reduction): self
    {
        if (!$this->reductions->contains($reduction)) {
            $this->reductions[] = $reduction;
            $reduction->setUser($this);
        }

        return $this;
    }

    public function removeReduction(Reduction $reduction): self
    {
        if ($this->reductions->removeElement($reduction)) {
            // set the owning side to null (unless already changed)
            if ($reduction->getUser() === $this) {
                $reduction->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Augmentation[]
     */
    public function getAugmentations(): Collection
    {
        return $this->augmentations;
    }

    public function addAugmentation(Augmentation $augmentation): self
    {
        if (!$this->augmentations->contains($augmentation)) {
            $this->augmentations[] = $augmentation;
            $augmentation->setUser($this);
        }

        return $this;
    }

    public function removeAugmentation(Augmentation $augmentation): self
    {
        if ($this->augmentations->removeElement($augmentation)) {
            // set the owning side to null (unless already changed)
            if ($augmentation->getUser() === $this) {
                $augmentation->setUser(null);
            }
        }

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
            $vente->setUser($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getUser() === $this) {
                $vente->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Panier[]
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): self
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers[] = $panier;
            $panier->setUser($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): self
    {
        if ($this->paniers->removeElement($panier)) {
            // set the owning side to null (unless already changed)
            if ($panier->getUser() === $this) {
                $panier->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getPayed(): Collection
    {
        return $this->Payed;
    }

    public function addPayed(Transaction $payed): self
    {
        if (!$this->Payed->contains($payed)) {
            $this->Payed[] = $payed;
            $payed->setTransmitter($this);
        }

        return $this;
    }

    public function removePayed(Transaction $payed): self
    {
        if ($this->Payed->removeElement($payed)) {
            // set the owning side to null (unless already changed)
            if ($payed->getTransmitter() === $this) {
                $payed->setTransmitter(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getGotPayed(): Collection
    {
        return $this->GotPayed;
    }

    public function addGotPayed(Transaction $gotPayed): self
    {
        if (!$this->GotPayed->contains($gotPayed)) {
            $this->GotPayed[] = $gotPayed;
            $gotPayed->setTransmittedTo($this);
        }

        return $this;
    }

    public function removeGotPayed(Transaction $gotPayed): self
    {
        if ($this->GotPayed->removeElement($gotPayed)) {
            // set the owning side to null (unless already changed)
            if ($gotPayed->getTransmittedTo() === $this) {
                $gotPayed->setTransmittedTo(null);
            }
        }

        return $this;
    }
    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }
    // public static function createFromPayload($username, array $payload)
    // {
    //     return new self(
    //         $username,
    //         $payload["roles"], // Added by default
    //         $payload['email']  // Custom
    //     );
    // }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Collection|Notification[]
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications[] = $notification;
            $notification->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getUser() === $this) {
                $notification->setUser(null);
            }
        }

        return $this;
    }
}

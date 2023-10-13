<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $civilite = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $pseudo = null;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Avis::class)]
    private Collection $avis;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: ConfigurationPC::class, orphanRemoval: true)]
    private Collection $configurations;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: AdresseFacturation::class, orphanRemoval: true)]
    private Collection $adressesFacturation;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: AdresseLivraison::class, orphanRemoval: true)]
    private Collection $adressesLivraison;

    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: Commande::class)]
    private Collection $commandes;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->configurations = new ArrayCollection();
        $this->adressesFacturation = new ArrayCollection();
        $this->adressesLivraison = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getUtilisateur() === $this) {
                $avi->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConfigurationPC>
     */
    public function getConfigurations(): Collection
    {
        return $this->configurations;
    }

    public function addConfiguration(ConfigurationPC $configuration): static
    {
        if (!$this->configurations->contains($configuration)) {
            $this->configurations->add($configuration);
            $configuration->setUtilisateur($this);
        }

        return $this;
    }

    public function removeConfiguration(ConfigurationPC $configuration): static
    {
        if ($this->configurations->removeElement($configuration)) {
            // set the owning side to null (unless already changed)
            if ($configuration->getUtilisateur() === $this) {
                $configuration->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AdresseFacturation>
     */
    public function getAdressesFacturation(): Collection
    {
        return $this->adressesFacturation;
    }

    public function addAdressesFacturation(AdresseFacturation $adressesFacturation): static
    {
        if (!$this->adressesFacturation->contains($adressesFacturation)) {
            $this->adressesFacturation->add($adressesFacturation);
            $adressesFacturation->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdressesFacturation(AdresseFacturation $adressesFacturation): static
    {
        if ($this->adressesFacturation->removeElement($adressesFacturation)) {
            // set the owning side to null (unless already changed)
            if ($adressesFacturation->getUtilisateur() === $this) {
                $adressesFacturation->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AdresseLivraison>
     */
    public function getAdressesLivraison(): Collection
    {
        return $this->adressesLivraison;
    }

    public function addAdressesLivraison(AdresseLivraison $adressesLivraison): static
    {
        if (!$this->adressesLivraison->contains($adressesLivraison)) {
            $this->adressesLivraison->add($adressesLivraison);
            $adressesLivraison->setUtilisateur($this);
        }

        return $this;
    }

    public function removeAdressesLivraison(AdresseLivraison $adressesLivraison): static
    {
        if ($this->adressesLivraison->removeElement($adressesLivraison)) {
            // set the owning side to null (unless already changed)
            if ($adressesLivraison->getUtilisateur() === $this) {
                $adressesLivraison->setUtilisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setUtilisateur($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getUtilisateur() === $this) {
                $commande->setUtilisateur(null);
            }
        }

        return $this;
    }
}

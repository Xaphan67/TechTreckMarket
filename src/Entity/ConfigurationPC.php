<?php

namespace App\Entity;

use App\Entity\ProduitConfig;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\ConfigurationPCRepository;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ConfigurationPCRepository::class)]
class ConfigurationPC
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\ManyToOne(inversedBy: 'configurations')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\OneToMany(mappedBy: 'configurationPC', targetEntity: ProduitConfig::class, orphanRemoval: true, cascade:["persist"])]
    private Collection $produitConfigs;

    public function __construct(string $nom)
    {
        $this->produitConfigs = new ArrayCollection();
        $this->setNom($nom);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection<int, ProduitConfig>
     */
    public function getProduitConfigs(): Collection
    {
        return $this->produitConfigs;
    }

    public function addProduitConfig(ProduitConfig $produitConfig): static
    {
        if (!$this->produitConfigs->contains($produitConfig)) {
            $this->produitConfigs->add($produitConfig);
            $produitConfig->setConfigurationPC($this);
        }

        return $this;
    }

    public function removeProduitsConfig(ProduitConfig $produitConfig): static
    {
        if ($this->produitConfigs->removeElement($produitConfig)) {
            // set the owning side to null (unless already changed)
            if ($produitConfig->getConfigurationPC() === $this) {
                $produitConfig->setConfigurationPC(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class, orphanRemoval: true)]
    private Collection $produits;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'sousCategories')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $categorieParent = null;

    #[ORM\OneToMany(mappedBy: 'categorieParent', targetEntity: self::class, orphanRemoval: true)]
    private Collection $sousCategories;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?bool $accueil = null;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
        $this->sousCategories = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }

    public function getCategorieParent(): ?self
    {
        return $this->categorieParent;
    }

    public function setCategorieParent(?self $categorieParent): static
    {
        $this->categorieParent = $categorieParent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getSousCategories(): Collection
    {
        return $this->sousCategories;
    }

    public function addSousCategory(self $sousCategory): static
    {
        if (!$this->sousCategories->contains($sousCategory)) {
            $this->sousCategories->add($sousCategory);
            $sousCategory->setCategorieParent($this);
        }

        return $this;
    }

    public function removeSousCategory(self $sousCategory): static
    {
        if ($this->sousCategories->removeElement($sousCategory)) {
            // set the owning side to null (unless already changed)
            if ($sousCategory->getCategorieParent() === $this) {
                $sousCategory->setCategorieParent(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isAccueil(): ?bool
    {
        return $this->accueil;
    }

    public function setAccueil(bool $accueil): static
    {
        $this->accueil = $accueil;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}

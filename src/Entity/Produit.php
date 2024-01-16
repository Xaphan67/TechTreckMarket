<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateLancement = null;

    #[ORM\Column(length: 150)]
    private ?string $designation = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resume = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descriptif = null;

    #[ORM\Column(length: 150)]
    private ?string $photo = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prix = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2, nullable: true)]
    private ?string $promotion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $ancienPrix = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $nouveauPrix = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Marque $marque = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categorie = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Avis::class, orphanRemoval: true)]
    private Collection $avis;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categorie $categoriePrincipale = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: ProduitCaracteristiqueTechnique::class)]
    private Collection $caracteristiquesTechniques;

    #[ORM\Column]
    private ?bool $archive = null;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->caracteristiquesTechniques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateLancement(): ?\DateTimeInterface
    {
        return $this->dateLancement;
    }

    public function setDateLancement(\DateTimeInterface $dateLancement): static
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(string $descriptif): static
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getPromotion(): ?string
    {
        return $this->promotion;
    }

    public function setPromotion(?string $promotion): static
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getAncienPrix(): ?string
    {
        return $this->ancienPrix;
    }

    public function setAncienPrix(?string $ancienPrix): static
    {
        $this->ancienPrix = $ancienPrix;

        return $this;
    }

    public function getNouveauPrix(): ?string
    {
        return $this->nouveauPrix;
    }

    public function setNouveauPrix(?string $nouveauPrix): static
    {
        $this->nouveauPrix = $nouveauPrix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getMarque(): ?Marque
    {
        return $this->marque;
    }

    public function setMarque(?Marque $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

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
            $avi->setProduit($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getProduit() === $this) {
                $avi->setProduit(null);
            }
        }

        return $this;
    }

    public function getCategoriePrincipale(): ?Categorie
    {
        return $this->categoriePrincipale;
    }

    public function setCategoriePrincipale(?Categorie $categoriePrincipale): static
    {
        $this->categoriePrincipale = $categoriePrincipale;

        return $this;
    }

    public function __toString()
    {
        return $this->designation;
    }

    /**
     * @return Collection<int, ProduitCaracteristiqueTechnique>
     */
    public function getCaracteristiquesTechniques(): Collection
    {
        return $this->caracteristiquesTechniques;
    }

    public function addCaracteristiquesTechnique(ProduitCaracteristiqueTechnique $caracteristiquesTechnique): static
    {
        if (!$this->caracteristiquesTechniques->contains($caracteristiquesTechnique)) {
            $this->caracteristiquesTechniques->add($caracteristiquesTechnique);
            $caracteristiquesTechnique->setProduit($this);
        }

        return $this;
    }

    public function removeCaracteristiquesTechnique(ProduitCaracteristiqueTechnique $caracteristiquesTechnique): static
    {
        if ($this->caracteristiquesTechniques->removeElement($caracteristiquesTechnique)) {
            // set the owning side to null (unless already changed)
            if ($caracteristiquesTechnique->getProduit() === $this) {
                $caracteristiquesTechnique->setProduit(null);
            }
        }

        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): static
    {
        $this->archive = $archive;

        return $this;
    }
}

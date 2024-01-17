<?php

namespace App\Entity;

use App\Repository\ProduitCaracteristiqueTechniqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitCaracteristiqueTechniqueRepository::class)]
class ProduitCaracteristiqueTechnique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $valeur = null;

    #[ORM\ManyToOne(inversedBy: 'caracteristiquesTechniques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'produit')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CaracteristiqueTechnique $caracteristiqueTechnique = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    public function setValeur(string $valeur): static
    {
        $this->valeur = $valeur;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getCaracteristiqueTechnique(): ?CaracteristiqueTechnique
    {
        return $this->caracteristiqueTechnique;
    }

    public function setCaracteristiqueTechnique(?CaracteristiqueTechnique $caracteristiqueTechnique): static
    {
        $this->caracteristiqueTechnique = $caracteristiqueTechnique;

        return $this;
    }

    public function __toString() {
        return $this->getCaracteristiqueTechnique()->getNom();
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }
}

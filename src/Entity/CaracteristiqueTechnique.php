<?php

namespace App\Entity;

use App\Repository\CaracteristiqueTechniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CaracteristiqueTechniqueRepository::class)]
class CaracteristiqueTechnique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'caracteristiqueTechnique', targetEntity: ProduitCaracteristiqueTechnique::class)]
    private Collection $produit;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
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

    /**
     * @return Collection<int, ProduitCaracteristiqueTechnique>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(ProduitCaracteristiqueTechnique $produit): static
    {
        if (!$this->produit->contains($produit)) {
            $this->produit->add($produit);
            $produit->setCaracteristiqueTechnique($this);
        }

        return $this;
    }

    public function removeProduit(ProduitCaracteristiqueTechnique $produit): static
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCaracteristiqueTechnique() === $this) {
                $produit->setCaracteristiqueTechnique(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->nom;
    }
}

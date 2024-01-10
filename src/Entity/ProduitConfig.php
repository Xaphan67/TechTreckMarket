<?php

namespace App\Entity;

use App\Repository\ProduitConfigRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitConfigRepository::class)]
class ProduitConfig
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    #[ORM\ManyToOne(inversedBy: 'produitsConfig')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ConfigurationPC $configurationPC = null;

    public function __construct(ConfigurationPC $configuration, Produit $produit, int $quantite)
    {
        $this->setConfigurationPC($configuration);
        $this->setProduit($produit);
        $this->setQuantite($quantite);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

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

    public function getConfigurationPC(): ?ConfigurationPC
    {
        return $this->configurationPC;
    }

    public function setConfigurationPC(?ConfigurationPC $configurationPC): static
    {
        $this->configurationPC = $configurationPC;

        return $this;
    }
}

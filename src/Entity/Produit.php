<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
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
    private ?bool $disponible = null;

    #[ORM\Column]
    private array $caracteristiquesTechniques = [];

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

    public function isDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): static
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getCaracteristiquesTechniques(): array
    {
        return $this->caracteristiquesTechniques;
    }

    public function setCaracteristiquesTechniques(array $caracteristiquesTechniques): static
    {
        $this->caracteristiquesTechniques = $caracteristiquesTechniques;

        return $this;
    }
}

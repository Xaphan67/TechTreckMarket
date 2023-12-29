<?php

namespace App\Entity;

use App\Repository\AdresseFacturationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseFacturationRepository::class)]
class AdresseFacturation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $numero = null;

    #[ORM\Column(length: 50)]
    private ?string $typeRue = null;

    #[ORM\Column(length: 100)]
    private ?string $rue = null;

    #[ORM\Column(length: 50)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\ManyToOne(inversedBy: 'adressesFacturation')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column]
    private ?bool $preferee = null;

    public function __construct($adresse = null)
    {
        if ($adresse) {
            $this->setUtilisateur($adresse["utilisateur"]);
            $this->setNumero($adresse["numero"]);
            $this->setTypeRue($adresse["typeRue"]);
            $this->setRue($adresse["rue"]);
            $this->setCodePostal($adresse["codePostal"]);
            $this->setVille($adresse["ville"]);
            $this->setPreferee($adresse["preferee"]);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }

    public function getTypeRue(): ?string
    {
        return $this->typeRue;
    }

    public function setTypeRue(string $typeRue): static
    {
        $this->typeRue = $typeRue;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

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

    public function isPreferee(): ?bool
    {
        return $this->preferee;
    }

    public function setPreferee(bool $preferee): static
    {
        $this->preferee = $preferee;

        return $this;
    }

    public function __toString()
    {
        return $this->getNumero() . ", " . $this->getTypeRue() . " " . $this->getRue() . " - " . $this->getCodePostal() . " " . $this->getVille();
    }
}

<?php

namespace App\Entity;

use App\Repository\AtelierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AtelierRepository::class)]
class Atelier {

    /**
     * Id atelier
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    /**
     * Libellé atelier
     */
    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * Nombre de places maximum atelier
     */
    #[ORM\Column]
    private ?int $nbPlacesMaxi = null;

    /**
     * Les thèmes de l'atelier
     */
    #[ORM\ManyToMany(mappedBy: 'atelier', targetEntity: Theme::class)]
    private $themes;

    /**
     * Les vacations de l'atelier
     */
    #[ORM\ManyToMany(mappedBy: 'atelier', targetEntity: Vacation::class)]
    private $vacations;

    /**
     * Les inscriptions de l'atelier
     */
    #[ORM\ManyToMany(mappedBy: 'atelier', targetEntity: Inscription::class)]
    private $inscriptions;

    /**
     * Créer une instance atelier
     */
    public function __construct() {
        $this->themes = new ArrayCollection();
        $this->vacations = new ArrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    /**
     * Retourne l'id de l'atelier
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Retourne le libellé de l'atelier
     */
    public function getLibelle(): ?string {
        return $this->libelle;
    }

    /**
     * définit le libellé de l'atelier
     */
    public function setLibelle(string $libelle): static {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Retourne le nombre de places maximum de l'atelier
     */
    public function getNbPlacesMaxi(): ?int {
        return $this->nbPlacesMaxi;
    }

    /**
     * Définit le nombre de places de l'atelier
     */
    public function setNbPlacesMaxi(int $nbPlacesMaxi): static {
        $this->nbPlacesMaxi = $nbPlacesMaxi;

        return $this;
    }
}

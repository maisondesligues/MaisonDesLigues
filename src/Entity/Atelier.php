<?php

namespace App\Entity;

use App\Repository\AtelierRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AtelierRepository::class)]
class Atelier {

    /**
     * Id Atelier
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    /**
     * Libellé Atelier
     */
    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    /**
     * Nombre de places maximum Atelier
     */
    #[ORM\Column]
    private ?int $nbPlacesMaxi = null;

    /**
     * Les thèmes de l'Atelier
     */
    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'ateliers')]
    private $themes;

    /**
     * Les vacations de l'Atelier
     */
    #[ORM\ManyToMany(inversedBy: 'ateliers', targetEntity: Vacation::class)]
    private $vacations;

    /**
     * Les inscriptions de l'Atelier
     */
    #[ORM\ManyToMany(mappedBy: 'ateliers', targetEntity: Inscription::class)]
    private $inscriptions;

    /**
     * Créer une instance Atelier
     */
    public function __construct() {
        $this->themes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->vacations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->inscriptions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Retourne l'id de l'Atelier
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Retourne le libellé de l'Atelier
     */
    public function getLibelle(): ?string {
        return $this->libelle;
    }

    /**
     * définit le libellé de l'Atelier
     */
    public function setLibelle(string $libelle): static {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Retourne le nombre de places maximum de l'Atelier
     */
    public function getNbPlacesMaxi(): ?int {
        return $this->nbPlacesMaxi;
    }

    /**
     * Définit le nombre de places de l'Atelier
     */
    public function setNbPlacesMaxi(int $nbPlacesMaxi): static {
        $this->nbPlacesMaxi = $nbPlacesMaxi;

        return $this;
    }

    /**
     * Retourne les themes d'Atelier
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }
}

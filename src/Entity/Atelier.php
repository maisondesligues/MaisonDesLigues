<?php

namespace App\Entity;

use App\Repository\AtelierRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AtelierRepository::class)]
class Atelier {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $nbPlacesMaxi = null;

    #[ORM\ManyToMany(targetEntity: Theme::class, inversedBy: 'ateliers')]
    private $themes;

    public function __construct() {
        $this->themes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getLibelle(): ?string {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNbPlacesMaxi(): ?int {
        return $this->nbPlacesMaxi;
    }

    public function setNbPlacesMaxi(int $nbPlacesMaxi): static {
        $this->nbPlacesMaxi = $nbPlacesMaxi;

        return $this;
    }

    public function getThemes() {
        return $this->themes;
    }
}

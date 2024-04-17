<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    /**
     * Id Theme
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    /**
     * Libelle Theme
     */
    #[ORM\Column(length: 255)]
    private ?string $libelle = null;
    
    /**
     * Les ateliers de Theme
     */
    #[ORM\ManyToMany(mappedBy: 'themes', targetEntity: Atelier::class)]
    private $ateliers;
    
    /**
     * Créer une instance Theme
     */
     public function __construct() {
        $this->ateliers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Retourne l'id Theme
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le libellé Theme
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * Définit le libellé Theme
     */
    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }
}

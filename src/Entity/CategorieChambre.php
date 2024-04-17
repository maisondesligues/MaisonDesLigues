<?php

namespace App\Entity;

use App\Repository\CategorieChambreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieChambreRepository::class)]
class CategorieChambre
{
    /**
     * Id CategorieChambre
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Libellé catégorie de CategorieChambre
     */
    #[ORM\Column(length: 255)]
    private ?string $libelleCategorie = null;

    /**
     * Les propositions de CategorieChambre
     */
    #[ORM\OneToMany(mappedBy: 'categorieChambre', targetEntity: Proposer::class)]
    private $propositions;

    /**
     * Les nuités de CategorieChambre
     */
    #[ORM\OneToMany(mappedBy: 'categorieChambre', targetEntity: Nuite::class)]
    private $nuites;
    
    /**
     * Retourne l'id de CategorieChambre
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le libelle de CategorieChambre
     */
    public function getLibelleCategorie(): ?string
    {
        return $this->libelleCategorie;
    }

    /**
     * Définit le libelle de CategorieChambre
     */
    public function setLibelleCategorie(string $libelleCategorie): static
    {
        $this->libelleCategorie = $libelleCategorie;

        return $this;
    }
}

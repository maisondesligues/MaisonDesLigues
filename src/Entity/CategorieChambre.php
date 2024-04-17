<?php

namespace App\Entity;

use App\Repository\CategorieChambreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieChambreRepository::class)]
class CategorieChambre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelleCategorie = null;

    #[ORM\OneToMany(mappedBy: 'categorieChambre', targetEntity: Proposer::class)]
    private $propositions;

    #[ORM\OneToMany(mappedBy: 'categorieChambre', targetEntity: Nuite::class)]
    private $nuites;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleCategorie(): ?string
    {
        return $this->libelleCategorie;
    }

    public function setLibelleCategorie(string $libelleCategorie): static
    {
        $this->libelleCategorie = $libelleCategorie;

        return $this;
    }
}

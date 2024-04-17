<?php

namespace App\Entity;

use App\Repository\ProposerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProposerRepository::class)]
class Proposer
{
    /**
     * Id Proposer
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Tarif nuitée Proposer
     */
    #[ORM\Column]
    private ?int $tarifNuite = null;

    /**
     * Les hotels de Proposer
     */
    #[ORM\ManyToOne(inversedBy: 'propositions', targetEntity: Hotel::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id', name: 'hotel_id')]
    private $hotels;

    /**
     * Les catégories chambre de Proposer
     */
    #[ORM\ManyToOne(inversedBy: 'propositions', targetEntity: CategorieChambre::class)]
    #[ORM\JoinColumn(referencedColumnName: 'id', name: 'categorie_id')]
    private $categorieChambre;

    /**
     * Retourne l'id de Proposer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le tarif nuitée de Proposer
     */
    public function getTarifNuite(): ?int
    {
        return $this->tarifNuite;
    }

    /**
     * Définit le tarif nuitée de Proposer
     */
    public function setTarifNuite(int $tarifNuite): static
    {
        $this->tarifNuite = $tarifNuite;

        return $this;
    }

    /**
     * Retourne les hotels de Proposer
     */
    public function getHotels() {
        return $this->hotels;
    }

    /**
     * Retourne les catégories chambre de Proposer
     */
    public function getCategorieChambre() {
        return $this->categorieChambre;
    }
}

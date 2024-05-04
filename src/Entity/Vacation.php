<?php

namespace App\Entity;

use App\Repository\VacationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VacationRepository::class)]
class Vacation
{
    /**
     * Id Vacation
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity=Atelier::class)
     * @ORM\JoinColumn(name="ateliers_id", referencedColumnName="id")
     */
    #[ORM\ManyToOne(inversedBy: 'vacations', targetEntity: Atelier::class)]
    private ?Atelier $ateliers = null;
    
    /**
     * Date heure début Vacation
     */
    #[ORM\Column(length: 255)]
    private ?string $dateHeureDebut = null;

    /**
     * Date heure fin Vacation
     */
    #[ORM\Column(length: 255)]
    private ?string $dateHeureFin = null;

    /**
     * Créer une instance Vacation
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date en heure de début de Vacation
     */
    public function getDateHeureDebut(): ?string
    {
        return $this->dateHeureDebut;
    }

    /**
     * Définit la date en heure de début de Vacation
     */
    public function setDateHeureDebut(string $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    /**
     * Retourne la date en heure de fin de Vacation
     */
    public function getDateHeureFin(): ?string
    {
        return $this->dateHeureFin;
    }

    // getter and setter for atelier
    public function getAtelier(): ?Atelier
    {
        return $this->ateliers;
    }

    public function setAtelier(?Atelier $atelier): self
    {
        $this->ateliers = $atelier;
        return $this;
    }

    /**
     * Définit la date en heure de fin de Vacation
     */
    public function setDateHeureFin(string $dateHeureFin): static
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }

    /**
     * Assigne l'Atelier à cette Vacation.
     */
    public function addAtelier(Atelier $atelier): self {
        $this->ateliers = $atelier;
        $atelier->addVacation($this);
        return $this;
    }
}

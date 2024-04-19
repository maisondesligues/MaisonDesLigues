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
     * Les ateliers de Vacation
     */
    #[ORM\ManyToOne(inversedBy: 'vacations', targetEntity: Atelier::class)]
    private ?Atelier $ateliers = null;
    
    /**
     * Date heure début Vacation
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureDebut = null;

    /**
     * Date heure fin Vacation
     */
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureFin = null;

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
    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    /**
     * Définit la date en heure de début de Vacation
     */
    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): static
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    /**
     * Retourne la date en heure de fin de Vacation
     */
    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    /**
     * Définit la date en heure de fin de Vacation
     */
    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): static
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
class Inscription
{
    /**
     * Id Inscription
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Date inscription Inscription
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    /**
     * Les ateliers d'Inscription
     */
    #[ORM\ManyToMany(inversedBy: 'inscriptions', targetEntity: Atelier::class)]
    private ?Collection $ateliers;

    /**
     * Les restaurations d'Inscription
     */
    #[ORM\ManyToMany(inversedBy: 'inscriptions', targetEntity: Restauration::class)]
    private ?Collection $restaurations;

    /**
     * Les nuités d'Inscription
     */
    #[ORM\OneToMany(mappedBy: 'inscription', targetEntity: Nuite::class)]
    private ?Collection $nuites;

    /**
     * Le compte d'Inscription
     */
    #[ORM\ManyToOne(inversedBy: 'inscription')]
    private ?Compte $compte = null;

    /**
     * Retourne l'id d'Inscription
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date d'Inscription
     */
    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    /**
     * Définit la date d'Inscription
     */
    public function setDateInscription(?\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }
}

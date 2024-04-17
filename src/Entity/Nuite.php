<?php

namespace App\Entity;

use App\Repository\NuiteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NuiteRepository::class)]
class Nuite
{
    /**
     * Id Nuite
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Date nuitée Nuite
     */
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $datenuitee = null;

    /**
     * Catégorie chambre Nuite
     */
    #[ORM\ManyToOne(inversedBy: 'nuites')]
    private ?CategorieChambre $categorie = null;

    /**
     * Hotel Nuite
     */
    #[ORM\ManyToOne(inversedBy: 'nuites')]
    private ?Hotel $hotel = null;

    /**
     * Inscription Nuite
     */
    #[ORM\ManyToOne(inversedBy: 'nuites')]
    private ?Inscription $inscription = null;

    /**
     * Retourne l'id de Nuite
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date nuitée de Nuite
     */
    public function getDatenuitee(): ?\DateTimeInterface
    {
        return $this->datenuitee;
    }

    /**
     * Définit la date nuitée de Nuite
     */
    public function setDatenuitee(\DateTimeInterface $datenuitee): static
    {
        $this->datenuitee = $datenuitee;

        return $this;
    }
}

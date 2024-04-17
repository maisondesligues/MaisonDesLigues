<?php

namespace App\Entity;

use App\Repository\RestaurationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurationRepository::class)]
class Restauration
{
    /**
     * Id Restauration
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Date restauration Restauration
     */
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateRestauration = null;

    /**
     * Type repas Restauration
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $typeRepas = null;

    /**
     * Les inscriptions de Restauration
     */
    #[ORM\ManyToMany(mappedBy: 'restaurations', targetEntity: Inscription::class)]
    private Collection $inscriptions;

    /**
     * Créer une instance Restauration
     */
    public function __construct()
    {
        $this->inscriptions = new ArrayCollection();
    }

    /**
     * Retourne l'id de Restauration
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne la date restauration de Restauration
     */
    public function getDateRestauration(): ?\DateTimeInterface
    {
        return $this->dateRestauration;
    }

    /**
     * Définit la date restauration de Restauration
     */
    public function setDateRestauration(?\DateTimeInterface $dateRestauration): static
    {
        $this->dateRestauration = $dateRestauration;

        return $this;
    }

    /**
     * Retourne le type repas de Restauration
     */
    public function getTypeRepas(): ?string
    {
        return $this->typeRepas;
    }

    /**
     * Définit le type repas de Restauration
     */
    public function setTypeRepas(?string $typeRepas): static
    {
        $this->typeRepas = $typeRepas;

        return $this;
    }
}

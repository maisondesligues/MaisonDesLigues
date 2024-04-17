<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte
{
    /**
     * Id Compte
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Email compte
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * Numéro de licence Compte
     */
    #[ORM\Column(length: 255)]
    private ?string $numlicence = null;

    /**
     * Mot de passe Compte
     */
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    /**
     * Rôles Compte
     */
    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    /**
     * Les inscriptions de Compte
     */
    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: Inscription::class)]
    private Collection $inscription;

    /**
     * Créer une instance Compte
     */
    public function __construct()
    {
        $this->inscription = new ArrayCollection();
    }

    /**
     * Retourne l'id de Compte
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l'email de Compte
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Définit l'email de Compte
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Retourne le numéro de licence de Compte
     */
    public function getNumlicence(): ?string
    {
        return $this->numlicence;
    }

    /**
     * Définit le numéro de licence de Compte
     */
    public function setNumlicence(string $numlicence): static
    {
        $this->numlicence = $numlicence;

        return $this;
    }

    /**
     * Retourne le mot de passe de Compte
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe de Compte
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Retourne les rôles de Compte
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Définit les rôles de Compte
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }
}

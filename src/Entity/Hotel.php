<?php

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HotelRepository::class)]
class Hotel {

    /**
     * Id Hotel
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom Hotel
     */
    #[ORM\Column(length: 255)]
    private ?string $pnom = null;

    /**
     * Adresse 1 Hotel
     */
    #[ORM\Column(length: 255)]
    private ?string $adresse1 = null;

    /**
     * Adresse 2 Hotel
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse2 = null;

    /**
     * Code Postal Hotel
     */
    #[ORM\Column(length: 50)]
    private ?string $cp = null;

    /**
     * Ville Hotel
     */
    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    /**
     * Téléphone Hotel
     */
    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    /**
     * Mail Hotel
     */
    #[ORM\Column(length: 255)]
    private ?string $mail = null;

    /**
     * Les propositions de Hotel
     */
    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: Proposer::class)]
    public $propositions;

    /**
     * Les nuites de Hotel
     */
    #[ORM\OneToMany(mappedBy: 'hotel', targetEntity: Nuite::class)]
    public $nuites;

    /**
     * Retourne l'id de Hotel
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Retourne le nom de Hotel
     */
    public function getPnom(): ?string {
        return $this->pnom;
    }

    /**
     * Définit le nom de Hotel
     */
    public function setPnom(string $pnom): static {
        $this->pnom = $pnom;

        return $this;
    }

    /**
     * Retourne l'adresse 1 de Hotel
     */
    public function getAdresse1(): ?string {
        return $this->adresse1;
    }

    /**
     * Définit l'adresse 1 de Hotel
     */
    public function setAdresse1(string $adresse1): static {
        $this->adresse1 = $adresse1;

        return $this;
    }

    /**
     * Retourne l'adresse 2 de Hotel
     */
    public function getAdresse2(): ?string {
        return $this->adresse2;
    }

    /**
     * Définit l'adresse 2 de Hotel
     */
    public function setAdresse2(?string $adresse2): static {
        $this->adresse2 = $adresse2;

        return $this;
    }

    /**
     * Retourne le code postal de Hotel
     */
    public function getCp(): ?string {
        return $this->cp;
    }

    /**
     * Définit le code postal de Hotel
     */
    public function setCp(string $cp): static {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Retourne la ville de Hotel
     */
    public function getVille(): ?string {
        return $this->ville;
    }

    /**
     * Définit la ville de Hotel
     */
    public function setVille(string $ville): static {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Retourne le téléphone de Hotel
     */
    public function getTel(): ?string {
        return $this->tel;
    }

    /**
     * Définit le téléphone de Hotel
     */
    public function setTel(string $tel): static {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Retourne le mail de Hotel
     */
    public function getMail(): ?string {
        return $this->mail;
    }

    /**
     * Définit le mail de Hotel
     */
    public function setMail(string $mail): static {
        $this->mail = $mail;

        return $this;
    }
}

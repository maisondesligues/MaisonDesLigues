<?php

namespace App\Entity;

use App\Repository\ProposerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProposerRepository::class)]
class Proposer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $tarifNuite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarifNuite(): ?int
    {
        return $this->tarifNuite;
    }

    public function setTarifNuite(int $tarifNuite): static
    {
        $this->tarifNuite = $tarifNuite;

        return $this;
    }
}

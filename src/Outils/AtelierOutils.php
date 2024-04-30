<?php

namespace App\Outils;

// ---------------------------------------------------------------------------------------------------

use App\Entity\Atelier;
use App\Entity\Vacation;
use App\Entity\Theme;
use App\Repository\AtelierRepository;
use Doctrine\ORM\EntityManagerInterface;

// ---------------------------------------------------------------------------------------------------

class AtelierOutils {
    private $entityManager;
    private $atelierRepository;

    /**
     * Constructeur de AtelierOutils
     */
    public function __construct(EntityManagerInterface $entityManager, AtelierRepository $atelierRepository) {
        $this->entityManager = $entityManager;
        $this->atelierRepository = $atelierRepository;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Créer un Atelier
     */
    public function createAtelier(string $libelle, int $nbPlacesMaxi) {

        $atelier = new Atelier();
        $atelier->setLibelle($libelle);
        $atelier->setNbPlacesMaxi($nbPlacesMaxi);

        $this->entityManager->persist($atelier);
        $this->entityManager->flush();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Créer un Theme
     */
    public function createTheme(string $libelle, int $atelierId) {

        $atelier = $this->atelierRepository->find($atelierId);

        $theme = new Theme();
        $theme->setLibelle($libelle);

        $atelier->addTheme($theme);

        $this->entityManager->persist($theme);
        $this->entityManager->persist($atelier);
        $this->entityManager->flush();


    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Créer une Vacation
     */
    public function createVacation(string $dateDebut, string $dateFin, int $atelierId) {

        $atelier = $this->atelierRepository->find($atelierId);

        $vacation = new Vacation();
        $vacation->setDateHeureDebut($dateDebut);
        $vacation->setDateHeureFin($dateFin);

        $atelier->addVacation($vacation);

        $this->entityManager->persist($vacation);
        $this->entityManager->persist($atelier);
        $this->entityManager->flush();


    }
}
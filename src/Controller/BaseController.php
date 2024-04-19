<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AtelierRepository;
use App\Repository\HotelRepository;
use App\Repository\CategorieChambreRepository;
use App\Service\AppParameters;

class BaseController extends AbstractController {

    #[Route('/accueil', name: 'app_base')]
    public function index(AtelierRepository $atelierRepository, HotelRepository $hotelRepository, CategorieChambreRepository $categorieChambreRepository, AppParameters $appParameters): Response {
        $ateliers = $atelierRepository->findAll();
        $hotels = $hotelRepository->findAll();
        $categoriesChambres = $categorieChambreRepository->findAll();
        $budgetSingle = $appParameters->getBudgetHotelSinglePrix();
        $budgetDouble = $appParameters->getBudgetHotelDoublePrix();
        $ibisSingle = $appParameters->getIbisHotelSinglePrix();
        $ibisDouble = $appParameters->getIbisHotelDoublePrix();
        return $this->render('Accueil.html.twig', [
                    'ateliers' => $ateliers,
                    'hotels' => $hotels,
                    'categoriesChambres' => $categoriesChambres,
                    'budgetSingle' => $budgetSingle,
                    'budgetDouble' => $budgetDouble,
                    'ibisSingle' => $ibisSingle,
                    'ibisDouble' => $ibisDouble,
        ]);
    }

    #[Route('/ateliers', name: 'ateliers_list')]
    public function listAteliers(AtelierRepository $atelierRepository): Response {
        $ateliers = $atelierRepository->findAll();
        dump($ateliers);

        return $this->render('list.html.twig', [
                    'ateliers' => $ateliers,
        ]);
    }
}
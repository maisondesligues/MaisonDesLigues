<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AtelierRepository;
use App\Repository\HotelRepository; // Assurez-vous d'importer le bon namespace pour HotelRepository

class CongresController extends AbstractController
{
    #[Route('/congres', name: 'app_congres')]
    public function index(AtelierRepository $atelierRepository, HotelRepository $hotelRepository): Response
    {
        $ateliers = $atelierRepository->findAll();
        $hotels = $hotelRepository->findAll();

        foreach ($ateliers as $atelier) {
            $themes = $atelier->getThemes();
        }

        foreach ($hotels as $hotel) {
            $categories = $hotel->getCategoriesChambre();
        }

        return $this->render('congres/index.html.twig', [
            'ateliers' => $ateliers,
            'hotels' => $hotels,
        ]);
    }
}
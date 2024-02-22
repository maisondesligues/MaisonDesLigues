<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    #[Route('/accueil', name: 'app_base')]
    public function index()
    {
        return $this->render('Accueil.html.twig');
    }
}
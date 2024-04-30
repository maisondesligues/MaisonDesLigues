<?php

namespace App\Controller;

use App\Repository\AtelierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Outils\FormTypes\AtelierType;
use App\Outils\FormTypes\VacationType;
use App\Outils\FormTypes\ThemeType;
use App\Outils\AtelierOutils;

// ---------------------------------------------------------------------------------------------------

class AtelierController extends AbstractController {

    private $atelierOutils;

    public function __construct (AtelierOutils $atelierOutils) {
        $this->atelierOutils = $atelierOutils;
    }

    #[Route('/admin/add_data', name: 'app_AddData')]
    public function addData(Request $request, AtelierRepository $atelierRepository): Response
    {
        
        // Récupère la liste des libellés dans la table Atelier
        $ateliers = $atelierRepository->findAll();
        $atelierChoices = [];

        foreach ($ateliers as $atelier) {
            $atelierChoices[$atelier->getLibelle()] = $atelier->getId();
        }

        // Création du formulaire
        $form = $this->createFormBuilder()

            // Combobox
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Atelier' => 'atelier',
                    'Thème' => 'theme',
                    'Vacation' => 'vacation',
                ],
                'expanded' => true,
            ])

            // On fait les appels aux classe FormTypes ---
            ->add('atelier', AtelierType::class, [
                'required' => false,
                'label'=>false
            ])

            ->add('theme', ThemeType::class, [
                'required' => false,
                'ateliers' => $atelierChoices
            ])

            ->add('vacation', VacationType::class, [
                'required' => false,
                'ateliers' => $atelierChoices
            ])

            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        // Atelier est cliqué d'office
        
        $form->handleRequest($request);

    // Si le formulaire est envoyé ----------------------------------------------------------------------------
        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();


            // Selon le bouton radio sélectionné
            switch ($formData['type']) {

            // Bouton Atelier ----------------------------------------------
                case 'atelier':
                    
                    // On récupère le libelle entré après le bouton radio Atelier
                    $libelleAtelier = $formData['atelier']['libelle'];
                    $nbPlacesAtelier = $formData['atelier']['nombrePlaces'];
                    
                // Si la case libelle ou nbPlaces est vice --------------------------------------------------------------------
                    if ($libelleAtelier == null || $nbPlacesAtelier == null) {
                        $this->addFlash('error','Les champs doivent être remplis');
                        return $this->redirectToRoute('app_AddData');
                    }

                    // On crée l'atelier
                    $this->atelierOutils->createAtelier($libelleAtelier, $nbPlacesAtelier);

                    break;

            // Bouton Theme ----------------------------------------------
                case 'theme':
                    
                    // On récupère le libelle entré après le bouton radio Theme
                    $libelleTheme = $formData['theme']['libelle'];
                    
                    // On récupère l'id de l'atelier sélectionner dans la combobox
                    $atelierIdTheme = $formData['theme']['atelier'];

                // Si la case libelle est vice ----------------------------------------------------------------------------
                    if ($libelleTheme == null) {
                        $this->addFlash('error','Les champs doivent être remplis');
                        return $this->redirectToRoute('app_AddData');
                    }
                    
                // Si les ateliers sont vide ----------------------------------------------------------------------------
                    if (empty($atelierChoices)) {

                        // On recharge la page avec un modal
                        $this->addFlash('danger','aucun atelier n"existe en bdd');
                        return $this->redirectToRoute('app_AddData');
                    }

                    // On crée le theme et on l'associe à l'atelier choisi
                    $this->atelierOutils->createTheme($libelleTheme, $atelierIdTheme);

                    break;

            // Bouton Vacation ----------------------------------------------
                case 'vacation':

                    // On récupère les dates entrées
                    $dateDebut = $formData['vacation']['dateDebut'];
                    $dateFin = $formData['vacation']['dateFin'];
                
                // Si la date de début est inférieur a celle de fin ------------------------------------------------------------
                    if ($dateDebut > $dateFin) {

                        // On recharge la page avec un modal
                        $this->addFlash('errordate','les dates ne correspondent pas');
                        return $this->redirectToRoute('app_AddData');
                    }

                // Si les ateliers sont vide ----------------------------------------------------------------------------
                    if (empty($atelierChoices)) {

                        // On recharge la page avec un modal
                        $this->addFlash('danger','aucun atelier n"existe en bdd');
                        return $this->redirectToRoute('app_AddData');
                    }

                    $formattedDateDebut = $dateDebut->format('Y-m-d H:i:s');
                    $formattedDateFin = $dateFin->format('Y-m-d H:i:s');

                    // On récupère l'id de l'atelier sélectionner dans la combobox
                    $atelierIdTheme = $formData['vacation']['atelier'];

                    // On crée la vacation et on l'associe à l'atelier choisi
                    $this->atelierOutils->CreateVacation($formattedDateDebut, $formattedDateFin, $atelierIdTheme);

                    break;
            }

            // On recharge la page avec un modal
            $this->addFlash('success', 'Données enregistrées avec succès.');
            return $this->redirectToRoute('app_AddData');
        }

        return $this->render('admin/addData.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

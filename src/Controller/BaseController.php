<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use App\Repository\VacationRepository;
use App\Repository\AtelierRepository;
use App\Repository\HotelRepository;
use App\Repository\CategorieChambreRepository;
use App\Service\MailerService;
use App\Entity\Compte;
use App\Outils\CompteOutils;
use App\Outils\LicencieOutils;
use App\Service\AppParameters;
use App\Outils\Security\PasswordManagement;
use App\Outils\Security\TokenManagement;

// ---------------------------------------------------------------------------------------------------

class BaseController extends AbstractController {
    
    private $mailerService;
    private $compteOutils;
    private $licencieOutils;
    private $passwordManagement;
    private $tokenManagement;

    /**
     * Constructeur du controlleur
     */
    public function __construct(MailerService $mailerService, CompteOutils $compteOutils, LicencieOutils $licencieOutils, 
        PasswordManagement $passwordManagement, TokenManagement $tokenManagement) {

        $this->mailerService = $mailerService;
        $this->compteOutils = $compteOutils;
        $this->licencieOutils = $licencieOutils;
        $this->passwordManagement = $passwordManagement;
        $this->tokenManagement = $tokenManagement;
    }
    
    // ---------------------------------------------------------------------------------------------------
    
    /**
     * Renvoie vers la page d'accueil de connexion de mdl
     */
    #[Route('', name: 'app_base')]
    public function index(){
        return $this->render('accueil/index.html.twig');
    }

    // ---------------------------------------------------------------------------------------------------
    
    /**
     * Renvoie vers la page d'accueil de MDL
     */
    #[Route('/accueil', name: 'accueil')]
    public function accueil(AtelierRepository $atelierRepository, HotelRepository $hotelRepository, 
        CategorieChambreRepository $categorieChambreRepository, VacationRepository $vacationRepository, 
        AppParameters $appParameters): Response {

        $ateliers = $atelierRepository->findAll();
        $hotels = $hotelRepository->findAll();
        $vacation = $vacationRepository->findAll();
        $categoriesChambres = $categorieChambreRepository->findAll();
        $budgetSingle = $appParameters->getBudgetHotelSinglePrix();
        $budgetDouble = $appParameters->getBudgetHotelDoublePrix();
        $ibisSingle = $appParameters->getIbisHotelSinglePrix();
        $ibisDouble = $appParameters->getIbisHotelDoublePrix();

        return $this->render('accueil/accueil.html.twig', [
                    'ateliers' => $ateliers,
                    'hotels' => $hotels,
                    'vacations'=> $vacation,
                    'categoriesChambres' => $categoriesChambres,
                    'budgetSingle' => $budgetSingle,
                    'budgetDouble' => $budgetDouble,
                    'ibisSingle' => $ibisSingle,
                    'ibisDouble' => $ibisDouble,
        ]);
    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie vers la page d'inscription d'un licencié
     */
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request): Response {
        
        // Récupère les numéros de licenciés de la table "Licencie"
        $licencies = $this->licencieOutils->getNumerosDeLicence();

        // Création du formulaire
        $form = $this->createFormBuilder()

            ->add('licence_number', TextType::class, ['label' => false])
            ->add('new_pass', PasswordType::class, ['label' => false])
            ->add('confirm_pass', PasswordType::class, ['label' => false])

            ->add('cancel_demand', SubmitType::class, ['label' => 'Annuler une demande', 'attr' => ['formnovalidate' => 'formnovalidate']])
            ->add('continue', SubmitType::class, ['label' => 'Enregistrer ma demande'])
            
            ->getForm();
            
        $form->handleRequest($request);

    // Si le formulaire est envoyé ----------------------------------------------------------------------------
        if ($form->isSubmitted()) {

            // On récupère les données du formulaire et on en prépare d'autres
            $formData = $form->getData();
            $licenceNumberInput = $formData['licence_number'];
            $password = $formData['confirm_pass'];
            $mail = "";

        // Si le bouton "Annuler" est cliqué ----------------------------------------------------------------------------
            if ($form->get('cancel_demand')->isClicked()) {

                // Renvoie vers la page d'annulation de demande
                return $this->redirectToRoute('app_cancelDemand');
            }

        // Si le bouton "Confirmer" du formulaire est cliqué ----------------------------------------------------------------------------
            if ($form->get('continue')->isClicked()) {

                // Si le mot de passe fait moins de 12 caractères et ne contient pas au moins une majuscule
                if (strlen($password) < 12 || !preg_match('/[A-Z]/', $password)) {

                    // Affichage d'un modal avec rechargement de la page
                    $this->addFlash('danger', "Le mot de passe doit contenir au moins 12 caractères dont une majuscule.");
                    return $this->redirectToRoute('app_inscription');
                }

            // On vérifie la licence ----------------------------------------------------------------------------
                $licenceFound = false;

                // Pour chaque numéro de licence dans la table licencie ----------------------------------------------------------------------------
                foreach ($licencies as $licencie) {

                    // Renvoie le numéro de licence vérifié en log
                    error_log("Vérification du numéro de licence: " . $licencie);

                // Si les numéro de licences correspondent ----------------------------------------------------------------------------
                    if ($licencie == $licenceNumberInput) {

                        // La licence est trouvée, on récupère le mail du licencié
                        $licenceFound = true;
                        $mail = $this->licencieOutils->getEmailDeLicenceNumber($licencie);

                        // Sort de la boucle
                        break;
                    }
                }

            // Si la licence n'est pas trouvée ----------------------------------------------------------------------------
                if (!$licenceFound) {

                    // Renvoie vers l'accueil avec affichage d'un modal
                    $this->addFlash(
                        'danger',
                        'Le numéro de licence saisi ne correspond pas.'
                    );    
                    
                    // Renvoie vers la page d'accueil
                    return $this->redirectToRoute('accueil');
                }

            // Si les mot de passes correspondent ----------------------------------------------------------------------------
                if ($formData['new_pass'] === $formData['confirm_pass']) {

                    // Génère un token de 32 caractères en créer un lien de confirmation
                    $token = $this->compteOutils->createCompte($mail, $password, $licenceNumberInput, ['INSCRIT']);
                    $link = $this->generateUrl('app_routeDeConfirmation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                    // Envoie un mail au concerné licencié avec son lien de confirmation
                    $this->mailerService->sendEmail(
                        'mdl-no-reply@gmail.com',
                        $mail,
                        'Inscription',
                        'Votre inscription a été retenue, merci de cliquer sur le lien suivant pour confirmer votre inscription :' . $link
                    );

                    // Renvoie vers la page de confirmation de demande
                    return $this->redirectToRoute('app_demandeEnAttente');

                }

            // Si les mot de passes ne correspondent pas ----------------------------------------------------------------------------
                else {

                    // Affichage d'un modal et rechargement du formulaire
                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    return $this->redirectToRoute('app_inscription');

                }
            }
        }

        // Crée le formulaire et affiche la page d'inscription ----------------------------------------------------------------------------
        return $this->render('inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Route vers la page de confirmation d'un licencié, utilisé avec un token
     */
    #[Route('/confirm', name: 'app_routeDeConfirmation')]
    public function confirmAccount(Request $request): Response {

        // Récupère le token entré dans l'URL
        $token = $request->query->get('token');

        // Récupère le repository de Compte
        $repository = $this->getDoctrine()->getRepository(Compte::class);

        // On récupère le Compte associé au token
        $compte = $repository->findOneBy(['confirmationToken' => $token]);

        // Si le compte existe et si le token n'est pas expiré -----------------------------------------------------------------
        if ($compte && $compte->getTokenExpiresAt() > new \DateTime()) {

            // Annule le token de la Table
            $this->tokenManagement->confirmationToken($compte);

            // Renvoie vers la page d'accueil avec affichage d'un modal
            $this->addFlash('success', 'Votre compte a été confirmé.');
            return $this->redirectToRoute('accueil');
        } 
        
        // Le compte n'existe pas ou le token est expiré -----------------------------------------------------------------
        else {

            // Renvoie vers la page d'inscription avec affichage d'un modal
            $this->addFlash('error', 'Le token est invalide ou a expiré.');
            return $this->redirectToRoute('app_inscription');
        }
    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie vers la page d'annulation de la demande de création de compte
     */
    #[Route('/cancelDemand', name: 'app_cancelDemand')]
    public function cancelDemand(Request $request): Response {

        // Création du formulaire
        $form = $this->createFormBuilder()
            ->add('licence_number', TextType::class, ['label' => false])
            ->add('new_pass', PasswordType::class, ['label' => false])

            ->add('cancel_demand', SubmitType::class, ['label' => 'Annuler une demande'])
            ->getForm();
            
        $form->handleRequest($request);

    // Si le formulaire est envoyé ---------------------------------------------------------------------------------------------------
        if ($form->isSubmitted()) {

            // On récupère les données du formulaire
            $formData = $form->getData();
            $licenceNumberInput = $formData['licence_number'];
            $passwordInput = $formData['new_pass'];

        // On vérifie si l'identifiant existe dans la base de données ---------------------------------------------------------------------------------------------------
            $licenceFound = false;

            foreach ($this->licencieOutils->getNumerosDeLicenceComptes() as $comp) {

                // Si l'identifiant existe ---------------------------------------------------------------------------------------------------
                if ($comp == $licenceNumberInput) {
                    
                    $licenceFound = true;

                    // On sort de la boucle
                    break;
                }
            }

        // Si l'identifiant a été trouvé ---------------------------------------------------------------------------------------------------
            if ($licenceFound) {

                // On vérifie si le mot de passe enregisté est le même que dans la base de données
                $mdpCheck = $this->passwordManagement->verifierMDP($licenceNumberInput, $passwordInput);

            // Si le mot de passe est bon --------------------------------------------------------------------------------------------------
                if ($mdpCheck) {

                    // On supprime le compte de la BDD et on renvoie vers l'accueil avec l'affichage d'un modal
                    $this->compteOutils->deleteCompte($licenceNumberInput);
                    $this->addFlash('successSuprr', 'mot de passe invalide');
                    return $this->redirectToRoute('accueil');
                }
                
            // Si le mot de passe n'est pas bon ---------------------------------------------------------------------------------------------------
                else {

                    // On renvoie vers l'accueil en affichant un modal
                    $this->addFlash('erreurMdp', 'mot de passe invalide');
                    return $this->redirectToRoute('accueil');
                }

            }
            
        // Si l'identifiant n'a pas été trouvé ---------------------------------------------------------------------------------------------------
            else {

                // On renvoie vers l'accueil en affichant un modal
                $this->addFlash('danger', 'Aucun compte ne correspond a cet identifiant');
                return $this->redirectToRoute('accueil');
            }

        }
        
        // Crée le formulaire et affiche la page d'annulation de demande de création du compte
        return $this->render('cancelDemand.html.twig', [
            'form' => $form->createView(),
        ]);
    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie vers la page mot de passe oublié
     * 
     * Créer un token puis le stocker en bdd pour l'utilisateur courant
     * lui envoie un mail pour rénitialiser le mot de passe
     * le mail lui renverra un lien unique pour modifier son mot de passe
     */
    #[Route('/mdpoublie', name: 'app_mdpoublie')]
    public function mdpOublie(Request $request): Response {

        $form = $this->createFormBuilder()
            ->add('licence_number', TextType::class, ['label' => false, 'required' => false,])
            ->add('adresse_mail', TextType::class, ['label' => false, 'required' => false,])

            ->add('continue', SubmitType::class, ['label' => 'Enregistrer ma demande'])
            ->getForm();
            
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $formData = $form->getData();
            $licenceNumber = $formData['licence_number'];
            $adresseMail = $formData['adresse_mail'];

            if (!$licenceNumber && !$adresseMail) {

                $this->addFlash('error', 'Vous devez fournir votre numéro de licence ou votre adresse email.');
            } else {

                // on vérifie le numéro du licencié ou l'adresse mail
                if (true) {
                                
                    // Mail a envoyer
                    // Token a sauvegarder en bdd avec une date limite
                    // Envoyer le token par email avec la route vers le formulaire de rénitialisation de mot de passe
                    return $this->redirectToRoute('app_demandeEnAttente');

                } else {

                    $this->addFlash('error', 'Aucun utilisateur trouvé avec ces informations.');
                }
            }
        }

        return $this->render('securite/mdpOubliee.html.twig', [
            'form' => $form->createView(),
        ]);
    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Affiche la page des demandes prise en compte
     */
    #[Route('/demandeEnAttente', name: 'app_demandeEnAttente')]
    public function priseencompte(): Response {

        return $this->render('demandeenattente.html.twig');
    }

// ---------------------------------------------------------------------------------------------------

    #[Route('/renitialiserMotDePasse/{token}', name: 'renitialiser_motdepasse')]
    public function resetPassword($token, Request $request): Response {

        // Recherchez l'utilisateur par token et vérifiez que le token n'est pas expiré
        // Si le token n'est pas valide, on ne continue pas

        $form = $this->createFormBuilder()
            ->add('new_pass', PasswordType::class, ['label' => false])
            ->add('confirm_pass', PasswordType::class, ['label' => false])

            ->add('continue', SubmitType::class, ['label' => 'Continuer'])
            ->getForm();
            
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $formData = $form->getData();
            
            /**
             * Les mots de passe correspondent
             */
            if ($formData['new_pass'] === $formData['confirm_pass']) {

                // On enregistre le nouveau mdp dans la bdd
                // Envoie un mail de confirmation de confimation de changement de mot de passe
                return $this->redirectToRoute('app_demandeEnAttente');

            } else {

                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                // on recharge le formulaire
                return $this->redirectToRoute('renitialiser_motdepasse');

            }
            
        }

        return $this->render('securite/renitialiser_motdepasse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}


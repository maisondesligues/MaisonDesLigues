<?php

namespace App\Controller;

use App\Repository\VacationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AtelierRepository;
use App\Repository\HotelRepository;
use App\Repository\CategorieChambreRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Service\AppParameters;

class BaseController extends AbstractController {
    private $httpClient;
    private $mailerService;

    /**
     * Renvoie vers la page d'accueil du site web
     */
    #[Route('', name: 'app_base')]
    public function index(){
        return $this->render('accueil/index.html.twig');
    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Instancie l'utilisation de l'api
     */
    public function __construct(HttpClientInterface $httpClient, MailerService $mailerService) {
        $this->httpClient = $httpClient;
        $this->mailerService = $mailerService;
    }

// ---------------------------------------------------------------------------------------------------
    
    /**
     * Renvoie vers la page d'accueil de MDL
     */
    #[Route('/accueil', name: 'accueil')]
    public function accueil(AtelierRepository $atelierRepository, HotelRepository $hotelRepository, CategorieChambreRepository $categorieChambreRepository, VacationRepository $vacationRepository, AppParameters $appParameters): Response {
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
     * Route test affichage ateliers
     */
    #[Route('/ateliers', name: 'ateliers_list')]
    public function listAteliers(AtelierRepository $atelierRepository): Response {
        $ateliers = $atelierRepository->findAll();

        return $this->render('list.html.twig', [
                    'ateliers' => $ateliers,
        ]);
    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie vers la page d'inscription d'un licencié
     */
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request): Response {
        
        $licencies = $this->getNumerosDeLicence();

        $form = $this->createFormBuilder()
            ->add('licence_number', TextType::class, ['label' => false])
            ->add('new_pass', PasswordType::class, ['label' => false])
            ->add('confirm_pass', PasswordType::class, ['label' => false])

            ->add('cancel_demand', SubmitType::class, ['label' => 'Annuler une demande', 'attr' => ['formnovalidate' => 'formnovalidate']])
            ->add('continue', SubmitType::class, ['label' => 'Enregistrer ma demande'])
            ->getForm();
            
        $form->handleRequest($request);

        
        if ($form->isSubmitted()) {

            $formData = $form->getData();
            $licenceNumberInput = $formData['licence_number'];
            $mail = "";
            
            /**
             * Mot de passe oublié
             */
            if ($form->get('cancel_demand')->isClicked()) {

                return $this->redirectToRoute('app_cancelDemand');
            }

            if ($form->get('continue')->isClicked()) {
                
                // Vérification de licence
                $licenceFound = false;
                foreach ($licencies as $licencie) {
                    error_log("Vérification du numéro de licence: " . $licencie);

                    if ($licencie == $licenceNumberInput) {

                        $licenceFound = true;
                        $mail = $this->getEmailDeLicenceNumber($licencie);

                        break;
                    }
                }

                if (!$licenceFound) {
                    // Si aucun numéro de licence ne correspond, rediriger vers Accueil
                    error_log("Numéro de licence non trouvé: " . $licenceNumberInput);
                    
                    $this->addFlash(
                        'danger',
                        'Le numéro de licence saisi ne correspond pas.'
                    );    
                    
                    return $this->redirectToRoute('accueil');
                }

                /**
                 * Les mots de passe correspondent
                 */
                if ($formData['new_pass'] === $formData['confirm_pass']) {

                    // Génère un token de 32 caractères
                    $token = bin2hex(openssl_random_pseudo_bytes(16));

                    // enregistrer les infos dans la table client
                    $link = 'https://votre-domaine.com/confirm?token=' . $token;

                    // Mail a envoyer
                    $this->mailerService->sendEmail(
                        'mdl-no-reply@gmail.com',
                        $mail,
                        'Inscription',
                        'Votre inscription a été retenue, merci de cliquer sur le lien suivant pour confirmer votre inscription :'
                    );

                    // Enregistre le licencié dans la bdd
                    return $this->redirectToRoute('app_demandeEnAttente');

                } else {

                    $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                    // on recharge le formulaire
                    return $this->redirectToRoute('app_inscription');

                }
            }
        }

        return $this->render('inscription.html.twig', [
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
     * Annuler une demande
     */
    #[Route('/cancelDemand', name: 'app_cancelDemand')]
    public function cancelDemand(Request $request): Response {

        $form = $this->createFormBuilder()
            ->add('licence_number', TextType::class, ['label' => false])
            ->add('new_pass', PasswordType::class, ['label' => false])

            ->add('cancel_demand', SubmitType::class, ['label' => 'Annuler une demande'])
            ->getForm();
            
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $formData = $form->getData();

            // Vérifier si l'identifiant existe dans la base de données
            // Vérifier si le mot de passe enregisté est le même que dans la base de données

            // Si tout est bon, renvoie vers une pasge de confirmation

            // On supprime l'utilisateur de la bdd
            return $this->redirectToRoute('app_demandeEnAttente');

        }
        
        return $this->render('cancelDemand.html.twig', [
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
// ---------------------------------------------------------------------------------------------------

#[Route('/confirmationMail/{token}', name: 'app_confirmerMail')]
    public function confirmationMail($token): Response {

    }

// ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie la liste des numéros de licenciés
     */
    public function getNumerosDeLicence(): array {

        $response = $this->httpClient->request('GET', 'http://localhost:8888/api/licencies');
        $content = $response->toArray();

        $numerosDeLicence = [];
        foreach ($content['hydra:member'] as $licencie) {

            if (isset($licencie['numlicence'])) {
                $numerosDeLicence[] = $licencie['numlicence'];
            }
        }

        return $numerosDeLicence;
    }

    /**
     * Renvoie l'email du licencié passé en paramètre
     */
    public function getEmailDeLicenceNumber(int $licenceNumber): ?string {

        $response = $this->httpClient->request('GET', 'http://localhost:8888/api/licencies');

        $content = $response->toArray();

        foreach ($content['hydra:member'] as $licencie) {
            if ($licencie['numlicence'] === $licenceNumber) {
                return $licencie['mail'];
            }
        }

        return null;
    }
}


<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Security;


use App\Repository\ProposerRepository;
use App\Repository\VacationRepository;
use App\Repository\AtelierRepository;
use App\Repository\HotelRepository;
use App\Repository\RestaurationRepository;
use App\Repository\CategorieChambreRepository;
use App\Service\MailerService;
use App\Entity\Compte;
use App\Outils\CompteOutils;
use App\Outils\LicencieOutils;
use App\Service\AppParameters;
use App\Outils\Security\PasswordManagement;
use App\Outils\Security\TokenManagement;

// ---------------------------------------------------------------------------------------------------

class BaseController extends AbstractController
{

    private $mailerService;
    private $compteOutils;
    private $licencieOutils;
    private $passwordManagement;
    private $tokenManagement;
    private $security;

    /**
     * Constructeur du controlleur
     */
    public function __construct(
        MailerService $mailerService,
        CompteOutils $compteOutils,
        LicencieOutils $licencieOutils,
        PasswordManagement $passwordManagement,
        TokenManagement $tokenManagement,
        Security $security
    ) {

        $this->mailerService = $mailerService;
        $this->compteOutils = $compteOutils;
        $this->licencieOutils = $licencieOutils;
        $this->passwordManagement = $passwordManagement;
        $this->tokenManagement = $tokenManagement;
        $this->security = $security;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie vers la page d'accueil de connexion de mdl
     */
    #[Route('login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->render('accueil/connexion.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // Le code ici ne sera jamais exécuté, Symfony gérera la déconnexion automatiquement
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie vers la page d'accueil de MDL
     */
    #[Route('/accueil', name: 'accueil')]
    public function accueil(
        AtelierRepository $atelierRepository,
        HotelRepository $hotelRepository,
        CategorieChambreRepository $categorieChambreRepository,
        VacationRepository $vacationRepository,
        AppParameters $appParameters
    ): Response {

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
            'vacations' => $vacation,
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
    public function inscription(Request $request): Response
    {

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
     * Inscris un licencié aux congrès
     */
    #[Route('/inscription-congres', name: 'app_inscription_congres')]
    public function inscriptionCongres(Request $request, AtelierRepository $atelierRepository, HotelRepository $hotelRepository, ProposerRepository $proposerRepository, CategorieChambreRepository $categorieChambreRepository, RestaurationRepository $restaurationRepository) : Response
    {

        // Vérifie que l'utilisateur à le rôle d'inscrit
        $user = $this->security->getUser();
        if (!$user || $user->getRoles() !== ['INSCRIT']) {
            return $this->redirectToRoute('app_login');
        }

        $ateliers = $atelierRepository->findAll();
        $hotels = $hotelRepository->findAll();
        $restaurations = $restaurationRepository->findAll();
        $categorieChambres = $categorieChambreRepository->findAll();

        // Liste les ateliers présents en bdd
        $atelierChoices = [];
        foreach ($ateliers as $atelier) {
            $atelierChoices[$atelier->getLibelle()] = $atelier->getId();
        }

        // Liste les hotels présents en bdd
        $hotelChoices = [];
        foreach ($hotels as $hotel) {
            $hotelChoices[$hotel->getPnom()] = $hotel->getId();
        }

        // Récupère les catégories liées aux chambres
        $categorieChoices = [];
        foreach ($categorieChambres as $categorie) {
            $categorieChoices[$categorie->getLibelleCategorie()] = $categorie->getId();
        }

        // Récupère les restaurations
        $restauraionsChoices = [];
        foreach ($restaurations as $restauration) {
            $restauraionsChoices[$restauration->getTypeRepas()] = $restauration->getId();
        }

        // Création du formulaire
        $form = $this->createFormBuilder()

            ->add('email', EmailType::class, ['label' => 'Modifier votre adresse email', 'required' => false])
            ->add('ateliers', ChoiceType::class, [
                'choices' => $atelierChoices,
                'expanded' => true,
                'multiple' => true,
                'label' => 'Choisir les ateliers'
            ])
            ->add('dateNuiteeD', DateType::class, ['widget' => 'single_text', 'label' => 'Choisir la date de début de nuitée'])
            ->add('dateNuiteeF', DateType::class, ['widget' => 'single_text', 'label' => 'Choisir la date de fin de nuitée'])
            ->add('hotel', ChoiceType::class, ['choices' => $hotelChoices, 'label' => 'Choisir votre hôtel'])
            ->add('categorie', ChoiceType::class, ['choices' => $categorieChoices, 'label' => 'Choisir la catégorie de chambre'])
            ->add('restauration', ChoiceType::class, ['choices' => $restauraionsChoices, 'label' => 'Choisir la restauration'])
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer l\'inscription'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les données ici
            $data = $form->getData();
            $email = $data['email'];
            $ateliers = $data['ateliers'];
            $datenuiteed = $data['dateNuiteeD'];
            $datenuiteef = $data['dateNuiteeF'];
            $categorieId = $data['categorie'];
            $restaurationId = $data['restauration'];
            $hotelId = $data['hotel'];

            // Récupère l'hotel
            $hotel = $hotelRepository->find($hotelId);

            // Récupère la restauration
            $restauration = $restaurationRepository->find($restaurationId);

            // Récuper la catégorie
            $categorie = $categorieChambreRepository->find($categorieId);

            // R2cupère la liste des ateliers
            $ateliersChoisis = [];
            foreach ($ateliers as $atelierId) {
                $atelier = $atelierRepository->find($atelierId);
                if ($atelier) {
                    $ateliersChoisis[] = $atelier->getLibelle();
                }
            }

            // Calcul du nombre de nuits
            $interval = $datenuiteed->diff($datenuiteef);
            $nights = $interval->days;

        // Si la diff des nuits est incorrecte
            if ($nights < 1) {

                // Affichage d'un modal
                $this->addFlash('errornights', "Veuillez vérifier les dates. La date de fin doit être après la date de début.");
                return $this->redirectToRoute('app_inscription_congres');
            }

            // Récupérer le tarif pour l'hôtel choisi
            $tarif = $proposerRepository->findOneBy(['hotels' => $hotelId, 'categorieChambre' => $categorieId]);

        // Si tarif n'existe pas
            if (!$tarif) {

                // Affiche un modal si aucune offre n'existe pour cet hotel
                $this->addFlash('errorTarif', "Le tarif pour l'hôtel sélectionné n'est pas disponible.");
                return $this->redirectToRoute('app_inscription_congres');
            }

            // Renvoie le coût total des nuitées
            $totalAmount = $nights * $tarif->getTarifNuite();

            // Construction de la liste des ateliers
            $listeAteliers = implode(", ", $ateliersChoisis);

            // génère un token aléatoire
            $token = bin2hex(random_bytes(16));

            // Récupère le numéro du licencié
            $numerolicencie = $user->getNumlicence();

            // Génère un lien renvoyant le token et le numéro du licencié
            $link = $this->generateUrl('confirmation', ['token' => $token, 'numLicencie' => $numerolicencie], UrlGeneratorInterface::ABSOLUTE_URL);

            // Construction du message de l'email
            $messageEmail = "Merci pour votre inscription. Voici le résumé de votre inscription au congrès :\n";
            $messageEmail .= "Email : " . $user->getEmail() . "\n";
            $messageEmail .= "Hotel : " . $hotel->getPnom() . "\n";
            $messageEmail .= "Restauration : " . $restauration->getTypeRepas() . "\n";
            $messageEmail .= "Catégorie de chambre : " . $categorie->getLibelleCategorie() . "\n";
            $messageEmail .= "Nombre de nuits : " . $nights . "\n";
            $messageEmail .= "Ateliers choisis : " . $listeAteliers . "\n";
            $messageEmail .= "Montant dû : $totalAmount €.";
            $messageEmail .= "Lien de confirmation: $link ";

            // Envoi de l'email de confirmation
            $this->mailerService->sendEmail(
                'no-reply@votrecongres.com',
                $user->getEmail(),
                'Confirmation d\'inscription au congrès',
                $messageEmail
            );

            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $user->setEmail($email);

                // Enregistre le nouvel e-mail dans la base de données
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            }

            // renvoie vers l'accueil avec affichage d'un modal
            $this->addFlash('InscriCongres', 'Attente Validation Mail');
            return $this->redirectToRoute('app_inscription_congres');
        }

        return $this->render('inscription_congres.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * renvoie vers la page d'accueil après vérification du lien
     */
    #[Route('/confirmation/{token}/{numLicencie}', name: 'confirmation')]
    public function confirmation(Request $request, string $token, string $numLicencie): Response
    {
        $this->addFlash('successInscriCongres', 'Votre inscription a été confirmée avec succès.');
        return $this->redirectToRoute('accueil');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Route vers la page de confirmation d'un licencié, utilisé avec un token
     */
    #[Route('/confirm', name: 'app_routeDeConfirmation')]
    public function confirmAccount(Request $request): Response
    {

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
    public function cancelDemand(Request $request): Response
    {

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
     * lui envoie un mail pour rénitialiser le mot de passe
     * le mail lui renverra un lien unique pour modifier son mot de passe
     */
    #[Route('/mdpoublie', name: 'app_mdpoublie')]
    public function mdpOublie(Request $request): Response
    {

        // Création du formulaire
        $form = $this->createFormBuilder()
            ->add('licence_number', TextType::class, ['label' => false, 'required' => false,])
            ->add('adresse_mail', TextType::class, ['label' => false, 'required' => false,])

            ->add('continue', SubmitType::class, [
                'label' => 'Enregistrer ma demande',
                'attr' => [
                    'class' => 'text-white bg-yellow-500 hover:bg-red-500 focus:ring-4 focus:outline-none focus:ring-black font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-yellow-500 dark:hover:bg-yellow-500 dark:focus:ring-black'
                ]
            ])
            
            
            
            ->getForm();

        $form->handleRequest($request);

        // Si le formulaire est envoyé ---------------------------------------------------------------------------------------------------
        if ($form->isSubmitted()) {

            // récupère les données du formulaire
            $formData = $form->getData();
            $licenceNumber = $formData['licence_number'];
            $adresseMail = $formData['adresse_mail'];

            // Si aucune licence et adresse mail n'est rentrée ---------------------------------------------------------------------------------
            if (!$licenceNumber && !$adresseMail) {

                // Renvoie un modal et recharge la page
                $this->addFlash('danger', 'Vous devez fournir votre numéro de licence ou votre adresse email.');
                return $this->redirectToRoute('app_mdpoublie');
            }

            // Si l'un des deux ou les deux est rentré -----------------------------------------------------------------------------------------------
            else {

                // génère un token aléatoire
                $token = bin2hex(random_bytes(16));

                // Si l'adresse mail est entrée ------------------------------------------------------------------------------------------
                if ($adresseMail) {

                    // On récupère la liste des mails présent en BDD dans la table Compte
                    $listMail = $this->compteOutils->getMailsDeLicenceComptes();

                    // Pour chaque adresse mail dans la table Compte
                    foreach ($listMail as $mail) {

                        // Si le mail de la table est le même que celui entrée ------------------------------------------------------------------------------------------
                        if ($mail == $adresseMail) {

                            // Récupère le numéro du licencié à partir du mail
                            $numerolicencie = $this->compteOutils->getNumeroDeLicence($adresseMail);

                            // Génère un lien renvoyant le token et le numéro du licencié
                            $link = $this->generateUrl('renitialiser_motdepasse', ['token' => $token, 'numLicencie' => $numerolicencie], UrlGeneratorInterface::ABSOLUTE_URL);

                            // Envoie un mail au concerné licencié avec son lien de confirmation et renvoie sur la page de demande en attente
                            $this->mailerService->sendEmail(
                                'mdl-no-reply@gmail.com',
                                $adresseMail,
                                'Changement de mot de passe',
                                'Merci de cliquer sur ce lien pour changer votre mot de passe :' . $link
                            );
                            return $this->redirectToRoute('app_demandeEnAttente');
                        }

                        // Sinon, on sort de la boucle ------------------------------------------------------------------------------------------
                        else {
                            break;
                        }
                    }
                }


                // Si le numéro de licencié est entré ------------------------------------------------------------------------------
                if ($licenceNumber) {

                    // Récupère tout les numéros de licence de la table Compte
                    $listNumeroLicencies = $this->compteOutils->getNumerosDeLicenceComptes();

                    // Pour chaque numero de licencie dans la table Compte
                    foreach ($listNumeroLicencies as $numeroLicencies) {

                        // Si le numéro de licence de la table est le même que celui entrée ------------------------------------------------------------------------------------------
                        if ($numeroLicencies == $licenceNumber) {

                            // Récupère le mail a partir du numéro de licencié
                            $adresseMail = $this->compteOutils->getMailDeLicence($licenceNumber);

                            // Génère un lien renvoyant le token et le numéro du licencié
                            $link = $this->generateUrl('renitialiser_motdepasse', ['token' => $token, 'numLicencie' => $licenceNumber], UrlGeneratorInterface::ABSOLUTE_URL);

                            // Envoie un mail au concerné licencié avec son lien de confirmation et renvoie sur la page de demande en attente
                            $this->mailerService->sendEmail(
                                'mdl-no-reply@gmail.com',
                                $adresseMail,
                                'Changement de mot de passe',
                                'Merci de cliquer sur ce lien pour changer votre mot de passe :' . $link
                            );
                            return $this->redirectToRoute('app_demandeEnAttente');
                        }

                        // Sinon, on sort de la boucle ------------------------------------------------------------------------------------------
                        else {
                            break;
                        }
                    }
                }

                // Si ni le mail ni le numéro de licencié n'est trouvé ------------------------------------------------------------------------------
                else {

                    // Renvoie un modal
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
    public function priseencompte(): Response
    {

        return $this->render('demandeenattente.html.twig');
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie vers la page de rénitialisation de mot de passes
     * Contient un token unique et un numéro de licencié en lien
     */
    #[Route('/renitialiserMotDePasse', name: 'renitialiser_motdepasse')]
    public function resetPassword($token, Request $request): Response
    {

        // Récupère le token entré dans l'URL
        $token = $request->query->get('token');

        // Récupère le numéro de licencié entré dans l'URL
        $numLicencie = $request->query->get('numLicencie');

        // Récupère le mail du licencié
        $mailLicencie = $this->compteOutils->getMailDeLicence($numLicencie);

        // Crée le formulaire
        $form = $this->createFormBuilder()
            ->add('new_pass', PasswordType::class, ['label' => false])
            ->add('confirm_pass', PasswordType::class, ['label' => false])

            ->add('continue', SubmitType::class, ['label' => 'Continuer'])
            ->getForm();

        $form->handleRequest($request);

        // Si le formulaire est confirmé ---------------------------------------------------------------------------------------------------
        if ($form->isSubmitted()) {

            // On récupère les données du formulaire
            $formData = $form->getData();
            $newPass = $formData['new_pass'];
            $confirmPass = $formData['confirm_pass'];

            // Si les mots de passe correspondent ---------------------------------------------------------------------------------------------------
            if ($newPass === $confirmPass) {

                // On enregistre le nouveau mdp dans la bdd
                $this->compteOutils->updatePasswordByLicenceNumber($numLicencie, $newPass);

                // Envoie un mail de confirmation de confimation de changement de mot de passe et renvoie vers la page de demandes en attente
                $this->mailerService->sendEmail(
                    'mdl-no-reply@gmail.com',
                    $mailLicencie,
                    'Changement mot de passe',
                    'Votre mot de passe a été changé'
                );
                return $this->redirectToRoute('app_demandeEnAttente');

            }

            // Si les mots de passe ne correspondent pas -------------------------------------------------------------------------
            else {

                // Renvoie un modal d'erreur
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
            }

        }

        // Crée le formulaire et affiche la page de réniatialisation de mot de passe
        return $this->render('securite/renitialiser_motdepasse.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}


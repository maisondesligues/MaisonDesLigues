<?php

namespace App\Outils;

// ---------------------------------------------------------------------------------------------------

use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;

// ---------------------------------------------------------------------------------------------------

class CompteOutils {

    private $entityManager;

    /**
     * Constructeur de CompteOutils
     */
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Créer un compte et renvoie un token
     */
    public function createCompte(string $mail, string $password, string $licenceNumber, array $roles): string {

        $token = bin2hex(random_bytes(16));

        $compte = new Compte();
        $compte->setEmail($mail);
        $compte->setNumlicence($licenceNumber);

        $compte->setPassword(password_hash($password, PASSWORD_DEFAULT));
        $compte->setRoles($roles);
        $compte->setConfirmationToken($token);
        $compte->setTokenExpiresAt(new \DateTimeImmutable('+24 hours'));

        $this->entityManager->persist($compte);
        $this->entityManager->flush();

        return $token;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Supprime un compte de la bdd de part son numéro de licence
     */
    public function deleteCompte(string $numLicence){

        $compteRepository = $this->entityManager->getRepository(Compte::class);
        $compte = $compteRepository->findByLicenceNumber($numLicence);

        $this->entityManager->remove($compte);
        $this->entityManager->flush();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie la liste des mails de licenciés parmis la table Compte
     */
    public function getMailsDeLicenceComptes(): array {
        $comptes = $this->entityManager->getRepository(Compte::class)->findAll();
        $mailsDeLicence = [];
        foreach ($comptes as $compte) {
            $mailsDeLicence[] = $compte->getEmail();
        }
        return $mailsDeLicence;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie la liste des numéros de licenciés parmis la table Compte
     */
    public function getNumerosDeLicenceComptes(): array {
        $comptes = $this->entityManager->getRepository(Compte::class)->findAll();
        $numDeLicence = [];
        foreach ($comptes as $compte) {
            $numDeLicence[] = $compte->getNumlicence();
        }
        return $numDeLicence;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie le numéro du licencié grâce à son mail entré en paramètre
     */
    public function getNumeroDeLicence(string $mailLicencie): ?string {

        $compteRepository = $this->entityManager->getRepository(Compte::class);
        $compte = $compteRepository->findByEmail($mailLicencie);

        return $compte->getNumlicence();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie l'email du licencié grâce à son numéro entré en paramètre
     */
    public function getMailDeLicence(int $numLicencie): ?string {

        $compteRepository = $this->entityManager->getRepository(Compte::class);
        $compte = $compteRepository->findByLicenceNumber($numLicencie);

        return $compte->getEmail();
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Modifie le mot de passe d'un compte avec son numéro de licence
     */
    public function updatePasswordByLicenceNumber(int $licenceNumber, string $newPassword) {

        $compteRepository = $this->entityManager->getRepository(Compte::class);
        $compte = $compteRepository->findOneBy(['numlicence' => $licenceNumber]);

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $compte->setPassword($hashedPassword);

        $this->entityManager->flush();
    }
}
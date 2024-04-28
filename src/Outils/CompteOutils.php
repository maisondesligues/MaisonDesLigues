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
}
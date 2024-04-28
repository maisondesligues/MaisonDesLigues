<?php

namespace App\Outils\Security;

// ---------------------------------------------------------------------------------------------------

use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;

// ---------------------------------------------------------------------------------------------------

class TokenManagement{

    private $entityManager;

    /**
     * Constructeur de TokenManagement
     */
    public function __construct(EntityManagerInterface $entityManagerInterface){
        $this->entityManager = $entityManagerInterface;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie le token d'un Compte
     */
    public function getTokenByLicenceNumber(string $licenceNumber): ?string {
        $compte = $this->entityManager->getRepository(Compte::class)->findOneBy(['numlicence' => $licenceNumber]);
        return $compte ? $compte->getConfirmationToken() : null;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie true si le token du Compte est validé
     */
    public function getTokenValidation(string $token): bool {
        $compte = $this->entityManager->getRepository(Compte::class)->findOneBy(['confirmationToken' => $token]);
        return $compte ? $compte->getConfirmationToken() === null : false;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Valide la confirmation par mail d'un Compte
     */
    public function confirmationToken(Compte $compte) {

        // Mets a null les paramètres de token dans la table Compte
        $compte->setConfirmationToken(null);
        $compte->setTokenExpiresAt(null);
        $this->entityManager->flush();
    }
}
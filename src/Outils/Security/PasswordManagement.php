<?php

namespace App\Outils\Security;

// ---------------------------------------------------------------------------------------------------

use App\Entity\Compte;
use Doctrine\ORM\EntityManagerInterface;

// ---------------------------------------------------------------------------------------------------

class PasswordManagement{

    private $entityManager;

    /**
     * Constructeur de PasswordManagement
     */
    public function __construct(EntityManagerInterface $entityManagerInterface){
        $this->entityManager = $entityManagerInterface;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie true si le mot de passe hashé correspont au mot de passe entré
     */
    public function verifierMDP(string $licencie, string $submittedPassword): bool {
        
        $user = $this->entityManager->getRepository(Compte::class)->findOneBy(['numlicence' => $licencie]);
        if (password_verify($submittedPassword, $user->getPassword())) { return true; }
        return false;
    }
}
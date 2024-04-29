<?php

namespace App\Outils;

// ---------------------------------------------------------------------------------------------------

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\AppParameters;
use App\Entity\Compte;

// ---------------------------------------------------------------------------------------------------

class LicencieOutils {

    private $entityManager;
    private $httpClient;
    private $appParameters;

    /**
     * Constructeur de LicencieOutils
     */
    public function __construct(EntityManagerInterface $entityManager, HttpClientInterface $httpClientInterface, AppParameters $appParameters) {
        $this->entityManager = $entityManager;
        $this->httpClient = $httpClientInterface;
        $this->appParameters = $appParameters;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie l'email du licencié passé en paramètre
     */
    public function getEmailDeLicenceNumber(int $licenceNumber): ?string {

        $response = $this->httpClient->request('GET', $this->appParameters->getLienApi() . 'licencies');

        $content = $response->toArray();

        foreach ($content['hydra:member'] as $licencie) {
            if ($licencie['numlicence'] === $licenceNumber) {
                return $licencie['mail'];
            }
        }

        return null;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie la liste des numéros de licenciés parmis la table Compte
     */
    public function getNumerosDeLicenceComptes(): array {
        $comptes = $this->entityManager->getRepository(Compte::class)->findAll();
        $numerosDeLicence = [];
        foreach ($comptes as $compte) {
            $numerosDeLicence[] = $compte->getNumlicence();
        }
        return $numerosDeLicence;
    }

    // ---------------------------------------------------------------------------------------------------

    /**
     * Renvoie la liste des numéros de licenciés parmis la table Licencie
     */
    public function getNumerosDeLicence(): array {

        $response = $this->httpClient->request('GET', $this->appParameters->getLienApi() . 'licencies');
        $content = $response->toArray();

        $numerosDeLicence = [];
        foreach ($content['hydra:member'] as $licencie) {

            if (isset($licencie['numlicence'])) {
                $numerosDeLicence[] = $licencie['numlicence'];
            }
        }

        return $numerosDeLicence;
    }
}
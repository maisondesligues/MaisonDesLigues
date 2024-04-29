<?php
namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


/*
 * Obtiens les paramètres de l'application
 */
class AppParameters
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function getIbisHotelSinglePrix()
    {
        return $this->params->get('ibis_hotel_single_prix');
    }

    public function getIbisHotelDoublePrix()
    {
        return $this->params->get('ibis_hotel_double_prix');
    }
    
    public function getBudgetHotelSinglePrix()
    {
        return $this->params->get('budget_hotel_single_prix');
    }
    
    public function getBudgetHotelDoublePrix()
    {
        return $this->params->get('budget_hotel_double_prix');
    }
    
    public function getRepasAccompagnantPrix()
    {
        return $this->params->get('repas_accompagnant_prix');
    }
    
    public function getInscriptionCongresPrix()
    {
        return $this->params->get('inscription_congres_prix');
    }

    public function getAccueilParticipants()
    {
        return $this->params->get('accueil_participants');
    }

    public function getPleniere()
    {
        return $this->params->get('pleniere');
    }
    
    public function getInterventionAgefos()
    {
        return $this->params->get('intervention_agefos');
    }
    
    public function getAteliers()
    {
        return $this->params->get('ateliers');
    }
    
    public function getDejeunerSurPlace()
    {
        return $this->params->get('dejeuner_place');
    }
    
    public function getPause()
    {
        return $this->params->get('pause');
    }
    
    public function getReceptionMairieDeLille()
    {
        return $this->params->get('reception_mairie_de_lille');
    }
    
    public function getDiner()
    {
        return $this->params->get('diner');
    }
    
    public function getPleniereConclusion()
    {
        return $this->params->get('pleniere_conclu');
    }

    public function getLienApi()
    {
        return $this->params->get('lien_api');
    }
}

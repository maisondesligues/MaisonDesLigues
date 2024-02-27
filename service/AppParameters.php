<?php
namespace App\service;

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
}
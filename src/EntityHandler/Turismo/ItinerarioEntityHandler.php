<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Itinerario;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class ItinerarioEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return Itinerario::class;
    }
}
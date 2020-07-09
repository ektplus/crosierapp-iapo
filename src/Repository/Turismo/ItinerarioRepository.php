<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Itinerario;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class ItinerarioRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Itinerario::class;
    }


}

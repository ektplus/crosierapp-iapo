<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Agencia;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class AgenciaEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return Agencia::class;
    }
}
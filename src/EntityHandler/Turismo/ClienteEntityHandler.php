<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Cliente;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class ClienteEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return Cliente::class;
    }
}
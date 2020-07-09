<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Veiculo;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class VeiculoEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return Veiculo::class;
    }
}
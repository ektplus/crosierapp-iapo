<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Compra;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class CompraEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return Compra::class;
    }
}
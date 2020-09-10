<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Motorista;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;

/**
 * @author Carlos Eduardo Pauluk
 */
class MotoristaEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return Motorista::class;
    }

    public function beforeSave(/** @var Motorista $motorista */ $motorista)
    {
        $motorista->cpf = preg_replace("/[^0-9]/", "", $motorista->cpf);
    }


}
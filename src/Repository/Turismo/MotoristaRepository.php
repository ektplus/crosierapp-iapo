<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Motorista;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class MotoristaRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Motorista::class;
    }


}

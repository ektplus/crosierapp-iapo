<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Viagem;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class ViagemRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Viagem::class;
    }


}

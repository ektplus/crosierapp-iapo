<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Passageiro;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class PassageiroRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Passageiro::class;
    }


}

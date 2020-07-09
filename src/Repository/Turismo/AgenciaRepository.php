<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Agencia;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class AgenciaRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Agencia::class;
    }


}

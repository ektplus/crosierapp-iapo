<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Cliente;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class ClienteRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Cliente::class;
    }


}

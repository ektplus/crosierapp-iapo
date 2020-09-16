<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Compra;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class CompraRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Compra::class;
    }


}

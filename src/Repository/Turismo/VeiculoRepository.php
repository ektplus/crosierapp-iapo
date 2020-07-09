<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Veiculo;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class VeiculoRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Veiculo::class;
    }


}

<?php

namespace App\EntityHandler;

use App\Entity\Refeicao;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;

/**
 * EntityHandler para a entidade Refeicao.
 *
 * @package App\EntityHandler
 * @author Andreia Maritsa Azevedo
 */
class RefeicaoEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return Refeicao::class;
    }
}
<?php

namespace App\Repository;

use App\Entity\Refeicao;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @package App\Controller\Refeicao
 * @author Andreia Maritsa Azevedo
 */
class RefeicaoRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Refeicao::class;
    }
}
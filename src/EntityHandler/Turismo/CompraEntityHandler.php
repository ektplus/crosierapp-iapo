<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Compra;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;

/**
 * @author Carlos Eduardo Pauluk
 */
class CompraEntityHandler extends EntityHandler
{

    public function getEntityClass()
    {
        return Compra::class;
    }

    /**
     * @param $compra
     * @return mixed|void
     * @throws ViewException
     */
    public function beforeSave(/** @var Compra $compra */ $compra)
    {
        if (!$compra->status) {
            throw new ViewException('Status n/d para compra');
        }
    }


}
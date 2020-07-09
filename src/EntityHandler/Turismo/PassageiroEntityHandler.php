<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Passageiro;
use App\Repository\Turismo\PassageiroRepository;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;

/**
 * @author Carlos Eduardo Pauluk
 */
class PassageiroEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return Passageiro::class;
    }

    /**
     * @param $passageiro
     * @return mixed|void
     * @throws ViewException
     */
    public function beforeSave(/** @var Passageiro $passageiro */ $passageiro)
    {
        /** @var PassageiroRepository $repoPassageiro */
        $repoPassageiro = $this->doctrine->getRepository(Passageiro::class);

        $jaExiste = $repoPassageiro->findOneByFiltersSimpl(
            [
                ['viagem', 'EQ', $passageiro->getViagem()->getId()],
                ['id', 'NEQ', $passageiro->getId()],
                ['cpf', 'EQ', preg_replace("/[^0-9]/", "", $passageiro->getCpf())],
                ['rg', 'EQ', $passageiro->getRg()],
            ]
        );

        if ($jaExiste) {
            throw new ViewException('Passageiro com o mesmo CPF/RG já incluído nesta viagem');
        }

    }


}
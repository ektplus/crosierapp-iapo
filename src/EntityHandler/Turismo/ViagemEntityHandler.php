<?php

namespace App\EntityHandler\Turismo;

use App\Entity\Turismo\Viagem;
use CrosierSource\CrosierLibBaseBundle\EntityHandler\EntityHandler;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;

/**
 * @author Carlos Eduardo Pauluk
 */
class ViagemEntityHandler extends EntityHandler
{


    public function getEntityClass()
    {
        return Viagem::class;
    }

    /**
     * @param $viagem
     * @return mixed|void
     * @throws ViewException
     */
    public function beforeSave(/** @var Viagem $viagem */ $viagem)
    {
        $viagem->veiculo = $viagem->itinerario->veiculo;



        // Verificações concernentes ao veículo
        if ((int)((clone $viagem->dtHrRetorno)->setTime(0, 0, 0, 0)
                ->diff(
                    (clone $viagem->veiculo->dtVenctoDer)->setTime(0, 0, 0, 0), false)->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com "DER" vencida. Impossível cadastrar viagem.');
        }

        if ((int)((clone $viagem->dtHrRetorno)->setTime(0, 0, 0, 0)
                ->diff(
                    (clone $viagem->veiculo->dtVenctoAntt)->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com "ANTT" vencida. Impossível cadastrar viagem.');
        }

        if ((int)((clone $viagem->dtHrRetorno)->setTime(0, 0, 0, 0)
                ->diff(
                    (clone $viagem->veiculo->dtVenctoTacografo)->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com tacógrafo vencido. Impossível cadastrar viagem.');
        }

        if ((int)((clone $viagem->dtHrRetorno)->setTime(0, 0, 0, 0)
                ->diff(
                    (clone $viagem->veiculo->dtVenctoSeguro)->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com seguro vencido. Impossível cadastrar viagem.');
        }

        // Verificações concernentes ao motorista
        if ((int)((clone $viagem->dtHrRetorno)->setTime(0, 0, 0, 0)
                ->diff(
                    (clone $viagem->motorista->dtVenctoCnh)->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Motorista com CNH vencida. Impossível cadastrar viagem.');
        }

        if ((int)((clone $viagem->dtHrRetorno)->setTime(0, 0, 0, 0)
                ->diff(
                    (clone $viagem->motorista->dtVenctoCarteiraSaude)->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Motorista com carteira de saúde vencida. Impossível cadastrar viagem.');
        }

        if ((int)((clone $viagem->dtHrRetorno)->setTime(0, 0, 0, 0)
                ->diff(
                    (clone $viagem->motorista->dtValidadeCursoTranspPassag)->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Motorista com Curso de Transporte de Passageiros vencido. Impossível cadastrar viagem.');
        }


    }


}

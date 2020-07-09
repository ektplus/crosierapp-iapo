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
        $viagem->setVeiculo($viagem->getItinerario()->getVeiculo());
        // Verificações concernentes ao veículo
        if ((int)($viagem->getDtHrRetorno()->setTime(0, 0, 0, 0)
                ->diff(
                    $viagem->getVeiculo()->getDtVenctoDer()->setTime(0, 0, 0, 0), false)->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com "DER" vencida. Impossível cadastrar viagem.');
        }

        if ((int)($viagem->getDtHrRetorno()->setTime(0, 0, 0, 0)
                ->diff(
                    $viagem->getVeiculo()->getDtVenctoAntt()->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com "ANTT" vencida. Impossível cadastrar viagem.');
        }

        if ((int)($viagem->getDtHrRetorno()->setTime(0, 0, 0, 0)
                ->diff(
                    $viagem->getVeiculo()->getDtVenctoTacografo()->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com tacógrafo vencido. Impossível cadastrar viagem.');
        }

        if ((int)($viagem->getDtHrRetorno()->setTime(0, 0, 0, 0)
                ->diff(
                    $viagem->getVeiculo()->getDtVenctoSeguro()->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Veículo com seguro vencido. Impossível cadastrar viagem.');
        }

        // Verificações concernentes ao motorista
        if ((int)($viagem->getDtHrRetorno()->setTime(0, 0, 0, 0)
                ->diff(
                    $viagem->getMotorista()->getDtVenctoCnh()->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Motorista com CNH vencida. Impossível cadastrar viagem.');
        }

        if ((int)($viagem->getDtHrRetorno()->setTime(0, 0, 0, 0)
                ->diff(
                    $viagem->getMotorista()->getDtVenctoCarteiraSaude()->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Motorista com carteira de saúde vencida. Impossível cadastrar viagem.');
        }

        if ((int)($viagem->getDtHrRetorno()->setTime(0, 0, 0, 0)
                ->diff(
                    $viagem->getMotorista()->getDtValidadeCursoTranspPassag()->setTime(0, 0, 0, 0))->format("%r%a")) <= 0) {
            throw new ViewException('Motorista com Curso de Transporte de Passageiros vencido. Impossível cadastrar viagem.');
        }


    }


}
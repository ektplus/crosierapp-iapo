<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Viagem;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class ViagemRepository extends FilterRepository
{

    public function getEntityClass(): string
    {
        return Viagem::class;
    }

    public function buildSelect2CidadesOrigensViagensProgramadas() {
        $conn = $this->getEntityManager()->getConnection();
        $rs = $conn->fetchAll('SELECT cidade_origem, estado_origem FROM iapo_tur_itinerario WHERE id IN (SELECT itinerario_id FROM iapo_tur_viagem WHERE status = \'PROGRAMADA\') GROUP BY cidade_origem, estado_origem');

        $cidadesOrigens = [];
        foreach ($rs as $r) {
            $cidadesOrigens[] = [
                'id' => $r['cidade_origem'] . '-' . $r['estado_origem'],
                'text' => $r['cidade_origem'] . '-' . $r['estado_origem'],
            ];
        }
        return $cidadesOrigens;
    }


}

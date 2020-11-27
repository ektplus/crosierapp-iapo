<?php

namespace App\Repository\Turismo;

use App\Entity\Turismo\Viagem;
use CrosierSource\CrosierLibBaseBundle\Exception\ViewException;
use CrosierSource\CrosierLibBaseBundle\Repository\FilterRepository;
use CrosierSource\CrosierLibBaseBundle\Utils\DateTimeUtils\DateTimeUtils;

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

    public function buildSelect2CidadesOrigensViagensProgramadas()
    {
        $conn = $this->getEntityManager()->getConnection();
        $rs = $conn->fetchAllAssociative('SELECT cidade_origem, estado_origem FROM iapo_tur_itinerario WHERE id IN (SELECT itinerario_id FROM iapo_tur_viagem WHERE status = \'PROGRAMADA\') GROUP BY cidade_origem, estado_origem');

        $stmtDestinos = $conn->prepare('SELECT cidade_destino, estado_destino FROM iapo_tur_itinerario WHERE cidade_origem = :cidadeOrigem AND estado_origem = :estadoOrigem GROUP BY cidade_destino, estado_destino ORDER BY cidade_destino, estado_destino');

        $cidadesOrigens = [];
        foreach ($rs as $r) {
            $destinos = [];
            $stmtDestinos->bindValue('cidadeOrigem', $r['cidade_origem']);
            $stmtDestinos->bindValue('estadoOrigem', $r['estado_origem']);
            $stmtDestinos->execute();
            $rDestinos = $stmtDestinos->fetchAll();
            $destinos[] = [
                'id' => '',
                'text' => 'TODOS',
            ];
            foreach ($rDestinos as $rDestino) {
                $destinos[] = [
                    'id' => $rDestino['cidade_destino'] . '-' . $rDestino['estado_destino'],
                    'text' => $rDestino['cidade_destino'] . '-' . $rDestino['estado_destino'],
                ];
            }
            $cidadesOrigens[] = [
                'id' => $r['cidade_origem'] . '-' . $r['estado_origem'],
                'text' => $r['cidade_origem'] . '-' . $r['estado_origem'],
                'destinos' => $destinos
            ];
        }
        return $cidadesOrigens;
    }


    /**
     * @param string $dts
     * @param string $cidadeOrigem
     * @param string $cidadeDestino
     * @return mixed[]
     * @throws ViewException
     */
    public function findViagensBy(string $dts, string $cidadeOrigem, string $cidadeDestino)
    {
        try {
            $dtIni = DateTimeUtils::parseDateStr(substr($dts, 0, 10));
            $hoje = (new \DateTime());//->setTime(12, 0); // precisa levar em consideração viagens do mesmo dia porém que já passaram do horário
            if ($hoje->diff((clone $dtIni)->setTime(12, 0))->days > 0) {
                $dtIni = $hoje;
            }
            $params['dtIni'] = $dtIni->format('Y-m-d');
            $params['dtFim'] = DateTimeUtils::parseDateStr(substr($dts, 13, 10))->format('Y-m-d');

            $params['cidadeOrigem'] = substr($cidadeOrigem, 0, -3);
            $params['estadoOrigem'] = substr($cidadeOrigem, -2);
            if ($cidadeDestino !== '') {
                $params['cidadeDestino'] = substr($cidadeDestino, 0, -3);
                $params['estadoDestino'] = substr($cidadeDestino, -2);
            }
            $sql = 'SELECT v.id FROM iapo_tur_viagem v, iapo_tur_itinerario iti 
                    WHERE v.itinerario_id = iti.id AND 
                    v.status = \'PROGRAMADA\' AND 
                    iti.cidade_origem = :cidadeOrigem AND
                    iti.estado_origem = :estadoOrigem AND ';
            if ($cidadeDestino !== '') {
                $sql .= 'iti.cidade_destino = :cidadeDestino AND
                     iti.estado_destino = :estadoDestino AND ';
            }
            $sql .= 'date(v.dthr_saida) BETWEEN :dtIni AND :dtFim ORDER BY v.dthr_saida, iti.cidade_destino, iti.estado_destino';
            $rsViagens = $this->getEntityManager()->getConnection()->fetchAll($sql, $params);
            $viagens = [];
            foreach ($rsViagens as $rViagem) {
                $viagens[] = $this->find($rViagem['id']);
            }
            return $viagens;
        } catch (\Exception $e) {
            throw new ViewException('Erro ao pesquisar viagens');
        }
    }

    /**
     * @param Viagem $viagem
     * @return array
     * @throws ViewException
     */
    public function handlePoltronas(Viagem $viagem): array
    {
        try {
            $conn = $this->getEntityManager()->getConnection();
            $sqlCroqui = 'SELECT valor FROM cfg_app_config WHERE chave = \'croquis_dos_veiculos.json\'';
            $rsCroqui = $conn->fetchAssociative($sqlCroqui);
            $croquis = json_decode($rsCroqui['valor'], true);
            $veiculoCroqui = $viagem->veiculo->croqui;
            $croqui = $croquis['veiculos'][$veiculoCroqui];
            $poltronas = explode(',', $croqui);
            $sqlPassageiros = 'SELECT id, nome, poltrona FROM iapo_tur_passageiro WHERE viagem_id = :viagemId';
            $rsPassageiros = $conn->fetchAllAssociative($sqlPassageiros, ['viagemId' => $viagem->getId()]);
            $rPoltronas = [];
            foreach ($poltronas as $k => $poltrona) {
                $rPoltronas[$poltrona] = ['status' => 'desocupada'];
                foreach ($rsPassageiros as $passageiro) {
                    if ((int)($passageiro['poltrona'] ?? -1) === (int)$poltrona) {
                        $rPoltronas[$poltrona] = [
                            'status' => 'ocupada',
                            'nomePassageiro' => $passageiro['nome']
                        ];
                    }
                }
            }
            return $rPoltronas;
        } catch (\Throwable $e) {
            throw new ViewException('Erro ao verificar poltronas ocupadas');
        }
    }

    /**
     * @param Viagem $viagem
     * @param array $dadosPassageiros
     * @throws ViewException
     */
    public function checkRGs(Viagem $viagem, array $dadosPassageiros): void
    {
        foreach ($viagem->passageiros as $passageiro) {
            foreach ($dadosPassageiros as $dadosPassageiro) {
                if ($dadosPassageiro['rg'] === $passageiro->rg) {
                    throw new ViewException('Passageiro com RG ' . $passageiro->rg . ' já cadastrado na viagem');
                }
            }

        }
    }


}

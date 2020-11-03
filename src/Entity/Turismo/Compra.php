<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\CompraRepository")
 * @ORM\Table(name="iapo_tur_compra")
 *
 * @author Carlos Eduardo Pauluk
 */
class Compra implements EntityId
{

    use EntityIdTrait;

    /**
     *
     * @ORM\Column(name="dt_compra", type="datetime")
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    public ?\DateTime $dtCompra = null;

    /**
     *
     * @ORM\Column(name="status", type="string")
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $status = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Cliente")
     * @ORM\JoinColumn(name="cliente_id")
     * @Groups("entity")
     *
     * @var Cliente|null
     */
    public ?Cliente $cliente = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Viagem")
     * @ORM\JoinColumn(name="viagem_id")
     * @Groups("entity")
     *
     * @var Viagem|null
     */
    public ?Viagem $viagem = null;

    /**
     *
     * @ORM\Column(name="valor_total", type="decimal")
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorTotal = null;

    /**
     *
     * @ORM\Column(name="json_data", type="json")
     * @var null|array
     * @NotUppercase()
     * @Groups("entity")
     */
    public ?array $jsonData = null;


    /**
     * Pesquisa no array que contém as transações recebidas pelo postback do Pagar.me, qual a transação autorizada.
     * @return array|null
     */
    public function getPostbackTransacaoAprovada(): ?array
    {
        if ($this->jsonData['postbacks'] ?? false) {
            foreach ($this->jsonData['postbacks'] as $postback) {
                if (($postback['current_status'] ?? false) === 'authorized') {
                    return $postback;
                }
            }
        }
        return null;
    }


    public function getTotais()
    {
        $opcaoCompra = $this->jsonData['opcaoCompra'];
        $rDadosPassageiros = $this->jsonData['dadosPassageiros'] ?? [];

        $totais = [
            'passagens' => 0,
            'taxas' => 0,
            'bagagens' => 0,
            'geral' => 0,
        ];

        if ($opcaoCompra === 'selecionarPoltronas' or $opcaoCompra === 'passagens') {

            foreach ($rDadosPassageiros as $rDadoPassageiro) {
                $rDadoPassageiro['total_bagagens'] = 0;
                if ($rDadoPassageiro['qtdeBagagens'] > 1) {
                    $rDadoPassageiro['total_bagagens'] = bcmul($rDadoPassageiro['qtdeBagagens'] - 1, $this->viagem->valorBagagem, 2);
                    $totais['bagagens'] = bcadd($totais['bagagens'], $rDadoPassageiro['total_bagagens'], 2);
                }

                if ($opcaoCompra === 'selecionarPoltronas') {
                    $rDadoPassageiro['valorPassagem'] = $this->viagem->getValorPassagemComEscolhaPoltrona();
                } else {
                    $rDadoPassageiro['valorPassagem'] = $this->viagem->valorPoltrona;
                }

                $totais['passagens'] = bcadd($totais['passagens'], $rDadoPassageiro['valorPassagem'], 2);
                $totais['taxas'] = bcadd($totais['taxas'], $this->viagem->valorTaxas, 2);
                $totais['geral'] = $totais['passagens'] + $totais['taxas'] + $totais['bagagens'];

                $rDadoPassageiro['total'] = $rDadoPassageiro['total_bagagens'] + $this->viagem->valorTaxas + $rDadoPassageiro['valorPassagem'];
                $rDadoPassageiro['nome'] = mb_strtoupper($rDadoPassageiro['nome']);
                $dadosPassageiros[] = $rDadoPassageiro;
            }

        } else if ($opcaoCompra === 'bagagens') {
            $qtdeBagagens = $this->jsonData['qtdeBagagens'];
            $totais['bagagens'] = bcmul($this->viagem->valorBagagem, $qtdeBagagens, 2);
            $totais['geral'] = bcmul($this->viagem->valorBagagem, $qtdeBagagens, 2);
        }

        return $totais;
    }

}

<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\ViagemRepository")
 * @ORM\Table(name="iapo_tur_viagem")
 *
 * @author Carlos Eduardo Pauluk
 */
class Viagem implements EntityId
{

    use EntityIdTrait;

    /**
     *
     * @ORM\Column(name="num_pedido", type="string", nullable=false)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $pedido = null;

    /**
     *
     * @ORM\Column(name="dthr_saida", type="datetime", nullable=false)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    public ?\DateTime $dtHrSaida = null;

    /**
     *
     * @ORM\Column(name="dthr_retorno", type="datetime", nullable=false)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    public ?\DateTime $dtHrRetorno = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Veiculo")
     * @ORM\JoinColumn(name="veiculo_id", nullable=true)
     * @Groups("entity")
     *
     * @var Veiculo|null
     */
    public ?Veiculo $veiculo = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Itinerario")
     * @ORM\JoinColumn(name="itinerario_id", nullable=true)
     * @Groups("entity")
     *
     * @var Itinerario|null
     */
    public ?Itinerario $itinerario = null;

    /**
     *
     * @ORM\Column(name="flg_financeiro", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $flagFinanceiro = null;

    /**
     *
     * @ORM\Column(name="flg_contrato", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $flagContrato = null;

    /**
     *
     * @ORM\Column(name="valor", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valor = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Agencia")
     * @ORM\JoinColumn(name="agencia_id", nullable=true)
     * @Groups("entity")
     *
     * @var Agencia|null
     */
    public ?Agencia $agencia = null;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Motorista")
     * @ORM\JoinColumn(name="motorista_id", nullable=true)
     * @Groups("entity")
     *
     * @var Motorista|null
     */
    public ?Motorista $motorista = null;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     * @NotUppercase()
     * @var string|null
     */
    public ?string $obs = null;

    /**
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $status = null;

    /**
     *
     * @ORM\Column(name="valor_poltrona", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorPoltrona = null;

    /**
     *
     * @ORM\Column(name="valor_taxas", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorTaxas = null;

    /**
     *
     * @ORM\Column(name="valor_bagagem", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorBagagem = null;

    /**
     *
     * @ORM\Column(name="valor_escolha_poltrona", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorEscolhaPoltrona = null;

    /**
     *
     * @ORM\OneToMany(
     *      targetEntity="Passageiro",
     *      mappedBy="viagem",
     *      orphanRemoval=true)
     * @var null|Passageiro[]|array|Collection
     */
    public $passageiros = null;

    /**
     *
     * @ORM\OneToMany(
     *      targetEntity="Compra",
     *      mappedBy="viagem",
     *      orphanRemoval=true)
     * @var null|Compra[]|array|Collection
     */
    public $compras = null;

    /**
     *
     * @ORM\Column(name="json_data", type="json")
     * @var null|array
     * @NotUppercase()
     * @Groups("entity")
     */
    public ?array $jsonData = null;


    public function __construct()
    {
        $this->passageiros = new ArrayCollection();
        $this->compras = new ArrayCollection();
    }

    public function getValorPassagemComEscolhaPoltrona()
    {
        return bcadd($this->valorPoltrona, $this->valorEscolhaPoltrona, 2);
    }

    public function getValorPassagemComTaxas()
    {
        return bcadd($this->valorPoltrona, $this->valorTaxas, 2);
    }

    public function getValorPassagemComTaxasEEscolhaPoltrona()
    {
        return bcadd($this->getValorPassagemComTaxas(), $this->valorEscolhaPoltrona, 2);
    }

}

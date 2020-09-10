<?php

namespace App\Entity\Turismo;

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
    public ?string $pedido;

    /**
     *
     * @ORM\Column(name="dthr_saida", type="datetime", nullable=false)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    public ?\DateTime $dtHrSaida;

    /**
     *
     * @ORM\Column(name="dthr_retorno", type="datetime", nullable=false)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    public ?\DateTime $dtHrRetorno;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Veiculo")
     * @ORM\JoinColumn(name="veiculo_id", nullable=true)
     * @Groups("entity")
     *
     * @var Veiculo|null
     */
    public ?Veiculo $veiculo;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Itinerario")
     * @ORM\JoinColumn(name="itinerario_id", nullable=true)
     * @Groups("entity")
     *
     * @var Itinerario|null
     */
    public ?Itinerario $itinerario;

    /**
     *
     * @ORM\Column(name="flg_financeiro", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $flagFinanceiro;

    /**
     *
     * @ORM\Column(name="flg_contrato", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $flagContrato;

    /**
     *
     * @ORM\Column(name="valor", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valor;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Agencia")
     * @ORM\JoinColumn(name="agencia_id", nullable=true)
     * @Groups("entity")
     *
     * @var Agencia|null
     */
    public ?Agencia $agencia;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Motorista")
     * @ORM\JoinColumn(name="motorista_id", nullable=true)
     * @Groups("entity")
     *
     * @var Motorista|null
     */
    public ?Motorista $motorista;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $obs;

    /**
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $status;

    /**
     *
     * @ORM\Column(name="valor_poltrona", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorPoltrona;

    /**
     *
     * @ORM\Column(name="valor_taxas", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorTaxas;

    /**
     *
     * @ORM\Column(name="valor_bagagem", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $valorBagagem;

    /**
     *
     * @ORM\OneToMany(
     *      targetEntity="Passageiro",
     *      mappedBy="viagem",
     *      orphanRemoval=true)
     * @var null|Passageiro[]|array|Collection
     * @Groups("entity")
     */
    public $passageiros;


    public function __construct()
    {
        $this->passageiros = new ArrayCollection();
    }

}

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
 * @ORM\Table(name="rtr_tur_viagem")
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
    private $pedido;

    /**
     *
     * @ORM\Column(name="dthr_saida", type="datetime", nullable=false)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    private $dtHrSaida;

    /**
     *
     * @ORM\Column(name="dthr_retorno", type="datetime", nullable=false)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    private $dtHrRetorno;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Veiculo")
     * @ORM\JoinColumn(name="veiculo_id", nullable=true)
     * @Groups("entity")
     *
     * @var Veiculo|null
     */
    private $veiculo;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Itinerario")
     * @ORM\JoinColumn(name="itinerario_id", nullable=true)
     * @Groups("entity")
     *
     * @var Itinerario|null
     */
    private $itinerario;

    /**
     *
     * @ORM\Column(name="flg_financeiro", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $flagFinanceiro;

    /**
     *
     * @ORM\Column(name="flg_contrato", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $flagContrato;

    /**
     *
     * @ORM\Column(name="valor", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    private $valor;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Agencia")
     * @ORM\JoinColumn(name="agencia_id", nullable=true)
     * @Groups("entity")
     *
     * @var Agencia|null
     */
    private $agencia;

    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Motorista")
     * @ORM\JoinColumn(name="motorista_id", nullable=true)
     * @Groups("entity")
     *
     * @var Motorista|null
     */
    private $motorista;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $obs;

    /**
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $status;


    /**
     *
     * @ORM\OneToMany(
     *      targetEntity="Passageiro",
     *      mappedBy="viagem",
     *      orphanRemoval=true)
     * @var null|Passageiro[]|array|Collection
     * @Groups("entity")
     */
    private $passageiros;


    public function __construct()
    {
        $this->passageiros = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getPedido(): ?string
    {
        return $this->pedido;
    }

    /**
     * @param string|null $pedido
     * @return Viagem
     */
    public function setPedido(?string $pedido): Viagem
    {
        $this->pedido = $pedido;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtHrSaida(): ?\DateTime
    {
        return $this->dtHrSaida;
    }

    /**
     * @param \DateTime|null $dtHrSaida
     * @return Viagem
     */
    public function setDtHrSaida(?\DateTime $dtHrSaida): Viagem
    {
        $this->dtHrSaida = $dtHrSaida;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtHrRetorno(): ?\DateTime
    {
        return $this->dtHrRetorno;
    }

    /**
     * @param \DateTime|null $dtHrRetorno
     * @return Viagem
     */
    public function setDtHrRetorno(?\DateTime $dtHrRetorno): Viagem
    {
        $this->dtHrRetorno = $dtHrRetorno;
        return $this;
    }

    /**
     * @return Veiculo|null
     */
    public function getVeiculo(): ?Veiculo
    {
        return $this->veiculo;
    }

    /**
     * @param Veiculo|null $veiculo
     * @return Viagem
     */
    public function setVeiculo(?Veiculo $veiculo): Viagem
    {
        $this->veiculo = $veiculo;
        return $this;
    }

    /**
     * @return Itinerario|null
     */
    public function getItinerario(): ?Itinerario
    {
        return $this->itinerario;
    }

    /**
     * @param Itinerario|null $itinerario
     * @return Viagem
     */
    public function setItinerario(?Itinerario $itinerario): Viagem
    {
        $this->itinerario = $itinerario;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFlagFinanceiro(): ?string
    {
        return $this->flagFinanceiro;
    }

    /**
     * @param string|null $flagFinanceiro
     * @return Viagem
     */
    public function setFlagFinanceiro(?string $flagFinanceiro): Viagem
    {
        $this->flagFinanceiro = $flagFinanceiro;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFlagContrato(): ?string
    {
        return $this->flagContrato;
    }

    /**
     * @param string|null $flagContrato
     * @return Viagem
     */
    public function setFlagContrato(?string $flagContrato): Viagem
    {
        $this->flagContrato = $flagContrato;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getValor(): ?float
    {
        return $this->valor;
    }

    /**
     * @param float|null $valor
     * @return Viagem
     */
    public function setValor(?float $valor): Viagem
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * @return Agencia|null
     */
    public function getAgencia(): ?Agencia
    {
        return $this->agencia;
    }

    /**
     * @param Agencia|null $agencia
     * @return Viagem
     */
    public function setAgencia(?Agencia $agencia): Viagem
    {
        $this->agencia = $agencia;
        return $this;
    }

    /**
     * @return Motorista|null
     */
    public function getMotorista(): ?Motorista
    {
        return $this->motorista;
    }

    /**
     * @param Motorista|null $motorista
     * @return Viagem
     */
    public function setMotorista(?Motorista $motorista): Viagem
    {
        $this->motorista = $motorista;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getObs(): ?string
    {
        return $this->obs;
    }

    /**
     * @param string|null $obs
     * @return Viagem
     */
    public function setObs(?string $obs): Viagem
    {
        $this->obs = $obs;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return Viagem
     */
    public function setStatus(?string $status): Viagem
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return Passageiro[]|array|Collection|null
     */
    public function getPassageiros()
    {
        return $this->passageiros;
    }

    /**
     * @param Passageiro[]|array|Collection|null $passageiros
     * @return Viagem
     */
    public function setPassageiros($passageiros)
    {
        $this->passageiros = $passageiros;
        return $this;
    }


}

<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\ItinerarioRepository")
 * @ORM\Table(name="iapo_tur_itinerario")
 *
 * @author Carlos Eduardo Pauluk
 */
class Itinerario implements EntityId
{

    use EntityIdTrait;


    /**
     *
     * @ORM\Column(name="cidade_origem", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $origemCidade;

    /**
     *
     * @ORM\Column(name="estado_origem", type="string", nullable=true, length=2)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $origemEstado;

    /**
     *
     * @ORM\Column(name="cidade_destino", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $destinoCidade;

    /**
     *
     * @ORM\Column(name="estado_destino", type="string", nullable=true, length=2)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $destinoEstado;

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
     * @ORM\Column(name="preco_min", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    private $precoMin;

    /**
     *
     * @ORM\Column(name="preco_max", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    private $precoMax;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $obs;

    /**
     * Transient.
     * @Groups("entity")
     *
     * @var string|null
     */
    private $descricaoMontada;

    /**
     * @return string|null
     */
    public function getOrigemCidade(): ?string
    {
        return $this->origemCidade;
    }

    /**
     * @param string|null $origemCidade
     * @return Itinerario
     */
    public function setOrigemCidade(?string $origemCidade): Itinerario
    {
        $this->origemCidade = $origemCidade;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOrigemEstado(): ?string
    {
        return $this->origemEstado;
    }

    /**
     * @param string|null $origemEstado
     * @return Itinerario
     */
    public function setOrigemEstado(?string $origemEstado): Itinerario
    {
        $this->origemEstado = $origemEstado;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDestinoCidade(): ?string
    {
        return $this->destinoCidade;
    }

    /**
     * @param string|null $destinoCidade
     * @return Itinerario
     */
    public function setDestinoCidade(?string $destinoCidade): Itinerario
    {
        $this->destinoCidade = $destinoCidade;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDestinoEstado(): ?string
    {
        return $this->destinoEstado;
    }

    /**
     * @param string|null $destinoEstado
     * @return Itinerario
     */
    public function setDestinoEstado(?string $destinoEstado): Itinerario
    {
        $this->destinoEstado = $destinoEstado;
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
     * @return Itinerario
     */
    public function setVeiculo(?Veiculo $veiculo): Itinerario
    {
        $this->veiculo = $veiculo;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrecoMin(): ?float
    {
        return $this->precoMin;
    }

    /**
     * @param float|null $precoMin
     * @return Itinerario
     */
    public function setPrecoMin(?float $precoMin): Itinerario
    {
        $this->precoMin = $precoMin;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPrecoMax(): ?float
    {
        return $this->precoMax;
    }

    /**
     * @param float|null $precoMax
     * @return Itinerario
     */
    public function setPrecoMax(?float $precoMax): Itinerario
    {
        $this->precoMax = $precoMax;
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
     * @return Itinerario
     */
    public function setObs(?string $obs): Itinerario
    {
        $this->obs = $obs;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescricaoMontada(): ?string
    {
        $this->descricaoMontada = 'De ' . $this->getOrigemCidade() . '-' . $this->getOrigemEstado() . ' até ' . $this->getDestinoCidade() . '-' . $this->getDestinoEstado() . ' (' . $this->getVeiculo()->getApelido() . ')';
        return $this->descricaoMontada;
    }

    /**
     * @param string|null $descricaoMontada
     * @return Itinerario
     */
    public function setDescricaoMontada(?string $descricaoMontada): Itinerario
    {
        $this->descricaoMontada = $descricaoMontada;
        return $this;
    }


}

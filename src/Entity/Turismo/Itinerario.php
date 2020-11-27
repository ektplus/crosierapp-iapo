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
    public ?string $origemCidade = null;

    /**
     *
     * @ORM\Column(name="estado_origem", type="string", nullable=true, length=2)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $origemEstado = null;

    /**
     *
     * @ORM\Column(name="cidade_destino", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $destinoCidade = null;

    /**
     *
     * @ORM\Column(name="estado_destino", type="string", nullable=true, length=2)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $destinoEstado = null;

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
     * @ORM\Column(name="preco_min", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $precoMin = null;

    /**
     *
     * @ORM\Column(name="preco_max", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $precoMax = null;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $obs = null;



    /**
     * @return string|null
     */
    public function getDescricaoMontada(): ?string
    {
        return 'De ' . $this->origemCidade . '-' . $this->origemEstado . ' até ' . $this->destinoCidade . '-' . $this->destinoEstado . ' (' . $this->veiculo->apelido . ')';
    }


}

<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\VeiculoRepository")
 * @ORM\Table(name="iapo_tur_veiculo")
 *
 * @author Carlos Eduardo Pauluk
 */
class Veiculo implements EntityId
{

    use EntityIdTrait;


    /**
     *
     * @ORM\Column(name="prefixo", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $prefixo = null;

    /**
     *
     * @ORM\Column(name="apelido", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $apelido = null;

    /**
     *
     * @ORM\Column(name="placa", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $placa = null;

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
     * @ORM\Column(name="renavan", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $renavan = null;

    /**
     *
     * @ORM\Column(name="dt_vencto_der", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtVenctoDer = null;

    /**
     *
     * @ORM\Column(name="dt_vencto_antt", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtVenctoAntt = null;

    /**
     *
     * @ORM\Column(name="dt_vencto_tacografo", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtVenctoTacografo = null;

    /**
     *
     * @ORM\Column(name="dt_vencto_seguro", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtVenctoSeguro = null;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $obs = null;


}

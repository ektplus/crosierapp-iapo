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


}

<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use CrosierSource\CrosierLibBaseBundle\Utils\StringUtils\StringUtils;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\MotoristaRepository")
 * @ORM\Table(name="iapo_tur_motorista")
 *
 * @author Carlos Eduardo Pauluk
 */
class Motorista implements EntityId
{

    use EntityIdTrait;


    /**
     *
     * @ORM\Column(name="cpf", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $cpf;

    /**
     *
     * @ORM\Column(name="rg", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $rg;

    /**
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $nome;

    /**
     *
     * @ORM\Column(name="cnh", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $cnh;

    /**
     *
     * @ORM\Column(name="dt_vencto_cnh", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtVenctoCnh;

    /**
     *
     * @ORM\Column(name="dt_vencto_cart_saude", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtVenctoCarteiraSaude;

    /**
     *
     * @ORM\Column(name="dt_validade_curso_transp_passag", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtValidadeCursoTranspPassag;

    /**
     *
     * @ORM\Column(name="fone_fixo", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $foneFixo;

    /**
     *
     * @ORM\Column(name="fone_celular", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $foneCelular;

    /**
     *
     * @ORM\Column(name="fone_whatsapp", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $foneWhatsapp;

    /**
     *
     * @ORM\Column(name="fone_recados", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $foneRecados;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $obs;

}

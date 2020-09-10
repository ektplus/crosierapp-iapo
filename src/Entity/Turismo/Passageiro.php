<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use CrosierSource\CrosierLibBaseBundle\Utils\StringUtils\StringUtils;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\PassageiroRepository")
 * @ORM\Table(name="iapo_tur_passageiro")
 *
 * @author Carlos Eduardo Pauluk
 */
class Passageiro implements EntityId
{

    use EntityIdTrait;


    /**
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turismo\Viagem", inversedBy="passageiros")
     * @ORM\JoinColumn(name="viagem_id", nullable=true)
     * @Groups("entity")
     * @MaxDepth(1)
     *
     * @var Viagem|null
     */
    public ?Viagem $viagem;


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
     * @ORM\Column(name="dt_nascimento", type="datetime", nullable=true)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    public ?\DateTime $dtNascimento;

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
     * @ORM\Column(name="email", type="string", nullable=true)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $email;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $obs;

}

<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use CrosierSource\CrosierLibBaseBundle\Entity\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\AgenciaRepository")
 * @ORM\Table(name="iapo_tur_agencia")
 *
 * @author Carlos Eduardo Pauluk
 */
class Agencia implements EntityId
{

    use EntityIdTrait;

    /**
     *
     * @ORM\Column(name="nome", type="string", nullable=true)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $nome = null;

    /**
     *
     * @ORM\Column(name="email", type="string", nullable=true)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $email = null;

    /**
     *
     * @ORM\Column(name="perc_comissao", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    public ?float $percComissao = null;


    /**
     *
     * @ORM\Column(name="cep", type="string", nullable=true, length=9)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $cep = null;

    /**
     *
     * @ORM\Column(name="logradouro", type="string", nullable=true, length=200)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $logradouro = null;

    /**
     *
     * @ORM\Column(name="numero", type="string", nullable=true, length=200)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $numero = null;

    /**
     *
     * @ORM\Column(name="complemento", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $complemento = null;

    /**
     *
     * @ORM\Column(name="bairro", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $bairro = null;

    /**
     *
     * @ORM\Column(name="cidade", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $cidade = null;

    /**
     *
     * @ORM\Column(name="estado", type="string", nullable=true, length=2)
     * @var string|null
     *
     * @Groups("entity")
     */
    public ?string $estado = null;

    /**
     *
     * @ORM\Column(name="fone_fixo", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $foneFixo = null;

    /**
     *
     * @ORM\Column(name="fone_celular", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $foneCelular = null;

    /**
     *
     * @ORM\Column(name="fone_whatsapp", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $foneWhatsapp = null;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $obs = null;


    /**
     *
     * @ORM\ManyToOne(targetEntity="CrosierSource\CrosierLibBaseBundle\Entity\Security\User")
     * @ORM\JoinColumn(name="user_id", nullable=true)
     * @Groups("entity")
     *
     * @var User|null
     */
    public ?User $user = null;

}

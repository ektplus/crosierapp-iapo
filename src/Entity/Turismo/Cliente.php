<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Doctrine\Annotations\NotUppercase;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\ClienteRepository")
 * @ORM\Table(name="iapo_tur_cliente")
 *
 * @author Carlos Eduardo Pauluk
 */
class Cliente implements EntityId
{

    use EntityIdTrait;


    /**
     *
     * @ORM\Column(name="cpf", type="string")
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $cpf = null;

    /**
     *
     * @ORM\Column(name="rg", type="string")
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $rg = null;

    /**
     *
     * @ORM\Column(name="nome", type="string")
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $nome = null;


    /**
     *
     * @ORM\Column(name="dt_nascimento", type="date")
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    public ?\DateTime $dtNascimento = null;

    /**
     *
     * @ORM\Column(name="fone", type="string")
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $fone = null;

    /**
     *
     * @ORM\Column(name="celular", type="string")
     * @Groups("entity")
     *
     * @var string|null
     */
    public ?string $celular = null;

    /**
     *
     * @ORM\Column(name="email", type="string")
     * @Groups("entity")
     * @NotUppercase()
     *
     * @var string|null
     */
    public ?string $email = null;

    /**
     *
     * @ORM\Column(name="senha", type="string")
     * @Groups("entity")
     * @NotUppercase
     * @var string|null
     */
    public ?string $senha = null;

    /**
     *
     * @ORM\Column(name="json_data", type="json")
     * @var null|array
     * @NotUppercase()
     * @Groups("entity")
     */
    public ?array $jsonData = null;


}

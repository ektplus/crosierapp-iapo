<?php

namespace App\Entity;

use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Entidade 'Movimentação'.
 *
 * @ORM\Entity(repositoryClass="App\Repository\RefeicaoRepository")
 * @ORM\Table(name="ip_refeicao")
 *
 * @author Andreia Maritsa Azevedo
 */
class Refeicao implements EntityId
{

    use EntityIdTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", name="id")
     * @Groups("entity")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="colaborador_id")
     * @Groups("entity")
     *
     * @var Integer|null
     */
    private $colaborador_id;

    /**
     * @ORM\Column(type="date", nullable=true, name="data")
     * @Groups("entity")
     *
     * @var \DateTime|null
     */
    private $data;

    /**
     * @ORM\Column(type="integer", nullable=true, name="qtde")
     * @Groups("entity")
     *
     * @var Integer|null
     */
    private $qtde;

    /**
     * @ORM\Column(type="boolean", nullable=true, name="almoco")
     * @Groups("entity")
     *
     * @var boolean|null
     */
    private $almoco = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, name="jantar")
     * @Groups("entity")
     *
     * @var boolean|null
     */
    private $jantar = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, name="cafe_manha")
     * @Groups("entity")
     *
     * @var boolean|null
     */
    private $cafe_manha = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, name="cafe_tarde")
     * @Groups("entity")
     *
     * @var boolean|null
     */
    private $cafe_tarde = false;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="updated")
     * @Groups("entity")
     *
     * @var \DateTime|null
     */
    private $updated;

    /**
     * @ORM\Column(type="datetime", nullable=true, name="inserted")
     * @Groups("entity")
     *
     * @var \DateTime|null
     */
    private $inserted;

    /**
     * @ORM\Column(type="integer", nullable=true, name="user_inserted_id")
     * @Groups("entity")
     *
     * @var integer|null
     */
    private $user_inserted_id;

    /**
     * @ORM\Column(type="integer", nullable=true, name="user_updated_id")
     * @Groups("entity")
     *
     * @var integer|null
     */
    private $user_updated_id;

    /**
     * @ORM\Column(type="integer", nullable=true, name="estabelecimento_id")
     * @Groups("entity")
     *
     * @var integer|null
     */
    private $estabelecimento_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getColaboradorId(): ?int
    {
        return $this->colaborador_id;
    }

    /**
     * @param int $colaborador_id
     */
    public function setColaboradorId(int $colaborador_id): void
    {
        $this->colaborador_id = $colaborador_id;
    }

    /**
     * @return \DateTime|null
     */
    public function getData(): ?\DateTime
    {
        return $this->data;
    }

    /**
     * @param \DateTime|null $data
     */
    public function setData(?\DateTime $data): void
    {
        $this->data = $data;
    }

    /**
     * @return int|null
     */
    public function getQtde(): ?int
    {
        return $this->qtde;
    }

    /**
     * @param int|null $qtde
     */
    public function setQtde(?int $qtde): void
    {
        $this->qtde = $qtde;
    }

    /**
     * @return bool|null
     */
    public function getAlmoco(): ?bool
    {
        return $this->almoco;
    }

    /**
     * @param bool|null $almoco
     */
    public function setAlmoco(?bool $almoco): void
    {
        $this->almoco = $almoco;
    }

    /**
     * @return bool|null
     */
    public function getJantar(): ?bool
    {
        return $this->jantar;
    }

    /**
     * @param bool|null $jantar
     */
    public function setJantar(?bool $jantar): void
    {
        $this->jantar = $jantar;
    }

    /**
     * @return bool|null
     */
    public function getCafeManha(): ?bool
    {
        return $this->cafe_manha;
    }

    /**
     * @param bool|null $cafe_manha
     */
    public function setCafeManha(?bool $cafe_manha): void
    {
        $this->cafe_manha = $cafe_manha;
    }

    /**
     * @return bool|null
     */
    public function getCafeTarde(): ?bool
    {
        return $this->cafe_tarde;
    }

    /**
     * @param bool|null $cafe_tarde
     */
    public function setCafeTarde(?bool $cafe_tarde): void
    {
        $this->cafe_tarde = $cafe_tarde;
    }
}

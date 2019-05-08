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
 * @ORM\Table(name="ip_rh_refeicao")
 *
 * @author Andreia Maritsa Azevedo
 */
class Refeicao implements EntityId
{

    use EntityIdTrait;

    /**
     * @ORM\Column(type="integer", name="colaborador_id")
     * @Groups("entity")
     *
     * @var Integer|null
     */
    private $colaboradorId;

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
    private $cafeManha = false;

    /**
     * @ORM\Column(type="boolean", nullable=true, name="cafe_tarde")
     * @Groups("entity")
     *
     * @var boolean|null
     */
    private $cafeTarde = false;

    /**
     * @return null|Integer
     */
    public function getColaboradorId(): ?Integer
    {
        return $this->colaboradorId;
    }

    /**
     * @param null|Integer $colaboradorId
     * @return Refeicao
     */
    public function setColaboradorId(?Integer $colaboradorId): Refeicao
    {
        $this->colaboradorId = $colaboradorId;
        return $this;
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
     * @return Refeicao
     */
    public function setData(?\DateTime $data): Refeicao
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return null|Integer
     */
    public function getQtde(): ?Integer
    {
        return $this->qtde;
    }

    /**
     * @param null|Integer $qtde
     * @return Refeicao
     */
    public function setQtde(?Integer $qtde): Refeicao
    {
        $this->qtde = $qtde;
        return $this;
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
     * @return Refeicao
     */
    public function setAlmoco(?bool $almoco): Refeicao
    {
        $this->almoco = $almoco;
        return $this;
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
     * @return Refeicao
     */
    public function setJantar(?bool $jantar): Refeicao
    {
        $this->jantar = $jantar;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getCafeManha(): ?bool
    {
        return $this->cafeManha;
    }

    /**
     * @param bool|null $cafeManha
     * @return Refeicao
     */
    public function setCafeManha(?bool $cafeManha): Refeicao
    {
        $this->cafeManha = $cafeManha;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getCafeTarde(): ?bool
    {
        return $this->cafeTarde;
    }

    /**
     * @param bool|null $cafeTarde
     * @return Refeicao
     */
    public function setCafeTarde(?bool $cafeTarde): Refeicao
    {
        $this->cafeTarde = $cafeTarde;
        return $this;
    }


}

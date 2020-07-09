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
    private $prefixo;

    /**
     *
     * @ORM\Column(name="apelido", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $apelido;

    /**
     *
     * @ORM\Column(name="placa", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $placa;

    /**
     *
     * @ORM\Column(name="status", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $status;

    /**
     *
     * @ORM\Column(name="renavan", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $renavan;

    /**
     *
     * @ORM\Column(name="dt_vencto_der", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    private $dtVenctoDer;

    /**
     *
     * @ORM\Column(name="dt_vencto_antt", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    private $dtVenctoAntt;

    /**
     *
     * @ORM\Column(name="dt_vencto_tacografo", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    private $dtVenctoTacografo;

    /**
     *
     * @ORM\Column(name="dt_vencto_seguro", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    private $dtVenctoSeguro;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $obs;

    /**
     * @return string|null
     */
    public function getPrefixo(): ?string
    {
        return $this->prefixo;
    }

    /**
     * @param string|null $prefixo
     * @return Veiculo
     */
    public function setPrefixo(?string $prefixo): Veiculo
    {
        $this->prefixo = $prefixo;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getApelido(): ?string
    {
        return $this->apelido;
    }

    /**
     * @param string|null $apelido
     * @return Veiculo
     */
    public function setApelido(?string $apelido): Veiculo
    {
        $this->apelido = $apelido;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlaca(): ?string
    {
        return $this->placa;
    }

    /**
     * @param string|null $placa
     * @return Veiculo
     */
    public function setPlaca(?string $placa): Veiculo
    {
        $this->placa = $placa;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return Veiculo
     */
    public function setStatus(?string $status): Veiculo
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRenavan(): ?string
    {
        return $this->renavan;
    }

    /**
     * @param string|null $renavan
     * @return Veiculo
     */
    public function setRenavan(?string $renavan): Veiculo
    {
        $this->renavan = $renavan;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtVenctoDer(): ?\DateTime
    {
        return $this->dtVenctoDer;
    }

    /**
     * @param \DateTime|null $dtVenctoDer
     * @return Veiculo
     */
    public function setDtVenctoDer(?\DateTime $dtVenctoDer): Veiculo
    {
        $this->dtVenctoDer = $dtVenctoDer;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtVenctoAntt(): ?\DateTime
    {
        return $this->dtVenctoAntt;
    }

    /**
     * @param \DateTime|null $dtVenctoAntt
     * @return Veiculo
     */
    public function setDtVenctoAntt(?\DateTime $dtVenctoAntt): Veiculo
    {
        $this->dtVenctoAntt = $dtVenctoAntt;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtVenctoTacografo(): ?\DateTime
    {
        return $this->dtVenctoTacografo;
    }

    /**
     * @param \DateTime|null $dtVenctoTacografo
     * @return Veiculo
     */
    public function setDtVenctoTacografo(?\DateTime $dtVenctoTacografo): Veiculo
    {
        $this->dtVenctoTacografo = $dtVenctoTacografo;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtVenctoSeguro(): ?\DateTime
    {
        return $this->dtVenctoSeguro;
    }

    /**
     * @param \DateTime|null $dtVenctoSeguro
     * @return Veiculo
     */
    public function setDtVenctoSeguro(?\DateTime $dtVenctoSeguro): Veiculo
    {
        $this->dtVenctoSeguro = $dtVenctoSeguro;
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
     * @return Veiculo
     */
    public function setObs(?string $obs): Veiculo
    {
        $this->obs = $obs;
        return $this;
    }


}

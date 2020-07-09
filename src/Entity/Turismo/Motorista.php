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
    private $cpf;

    /**
     *
     * @ORM\Column(name="rg", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $rg;

    /**
     *
     * @ORM\Column(name="nome", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $nome;

    /**
     *
     * @ORM\Column(name="cnh", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $cnh;

    /**
     *
     * @ORM\Column(name="dt_vencto_cnh", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    private $dtVenctoCnh;

    /**
     *
     * @ORM\Column(name="dt_vencto_cart_saude", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    private $dtVenctoCarteiraSaude;

    /**
     *
     * @ORM\Column(name="dt_validade_curso_transp_passag", type="date", nullable=false)
     * @Groups("entityId")
     *
     * @var null|\DateTime
     */
    private $dtValidadeCursoTranspPassag;

    /**
     *
     * @ORM\Column(name="fone_fixo", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $foneFixo;

    /**
     *
     * @ORM\Column(name="fone_celular", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $foneCelular;

    /**
     *
     * @ORM\Column(name="fone_whatsapp", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $foneWhatsapp;

    /**
     *
     * @ORM\Column(name="fone_recados", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $foneRecados;

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
    public function getCpf(): ?string
    {
        $this->cpf = StringUtils::mascararCnpjCpf($this->cpf);
        return $this->cpf;
    }

    /**
     * @param string|null $cpf
     * @return Motorista
     */
    public function setCpf(?string $cpf): Motorista
    {
        $cpf = preg_replace("/[^0-9]/", "", $cpf);
        $this->cpf = $cpf;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRg(): ?string
    {
        return $this->rg;
    }

    /**
     * @param string|null $rg
     * @return Motorista
     */
    public function setRg(?string $rg): Motorista
    {
        $this->rg = $rg;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @param string|null $nome
     * @return Motorista
     */
    public function setNome(?string $nome): Motorista
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCnh(): ?string
    {
        return $this->cnh;
    }

    /**
     * @param string|null $cnh
     * @return Motorista
     */
    public function setCnh(?string $cnh): Motorista
    {
        $this->cnh = $cnh;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtVenctoCnh(): ?\DateTime
    {
        return $this->dtVenctoCnh;
    }

    /**
     * @param \DateTime|null $dtVenctoCnh
     * @return Motorista
     */
    public function setDtVenctoCnh(?\DateTime $dtVenctoCnh): Motorista
    {
        $this->dtVenctoCnh = $dtVenctoCnh;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtVenctoCarteiraSaude(): ?\DateTime
    {
        return $this->dtVenctoCarteiraSaude;
    }

    /**
     * @param \DateTime|null $dtVenctoCarteiraSaude
     * @return Motorista
     */
    public function setDtVenctoCarteiraSaude(?\DateTime $dtVenctoCarteiraSaude): Motorista
    {
        $this->dtVenctoCarteiraSaude = $dtVenctoCarteiraSaude;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtValidadeCursoTranspPassag(): ?\DateTime
    {
        return $this->dtValidadeCursoTranspPassag;
    }

    /**
     * @param \DateTime|null $dtValidadeCursoTranspPassag
     * @return Motorista
     */
    public function setDtValidadeCursoTranspPassag(?\DateTime $dtValidadeCursoTranspPassag): Motorista
    {
        $this->dtValidadeCursoTranspPassag = $dtValidadeCursoTranspPassag;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoneFixo(): ?string
    {
        return $this->foneFixo;
    }

    /**
     * @param string|null $foneFixo
     * @return Motorista
     */
    public function setFoneFixo(?string $foneFixo): Motorista
    {
        $this->foneFixo = $foneFixo;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoneCelular(): ?string
    {
        return $this->foneCelular;
    }

    /**
     * @param string|null $foneCelular
     * @return Motorista
     */
    public function setFoneCelular(?string $foneCelular): Motorista
    {
        $this->foneCelular = $foneCelular;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoneWhatsapp(): ?string
    {
        return $this->foneWhatsapp;
    }

    /**
     * @param string|null $foneWhatsapp
     * @return Motorista
     */
    public function setFoneWhatsapp(?string $foneWhatsapp): Motorista
    {
        $this->foneWhatsapp = $foneWhatsapp;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoneRecados(): ?string
    {
        return $this->foneRecados;
    }

    /**
     * @param string|null $foneRecados
     * @return Motorista
     */
    public function setFoneRecados(?string $foneRecados): Motorista
    {
        $this->foneRecados = $foneRecados;
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
     * @return Motorista
     */
    public function setObs(?string $obs): Motorista
    {
        $this->obs = $obs;
        return $this;
    }


}

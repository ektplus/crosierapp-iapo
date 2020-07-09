<?php

namespace App\Entity\Turismo;

use CrosierSource\CrosierLibBaseBundle\Entity\EntityId;
use CrosierSource\CrosierLibBaseBundle\Entity\EntityIdTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 *
 * @ORM\Entity(repositoryClass="App\Repository\Turismo\AgenciaRepository")
 * @ORM\Table(name="rtr_tur_agencia")
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
    private $nome;

    /**
     *
     * @ORM\Column(name="email", type="string", nullable=true)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $email;

    /**
     *
     * @ORM\Column(name="perc_comissao", type="decimal", nullable=true)
     * @Groups("entity")
     *
     * @var float|null
     */
    private $percComissao;


    /**
     *
     * @ORM\Column(name="cep", type="string", nullable=true, length=9)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $cep;

    /**
     *
     * @ORM\Column(name="logradouro", type="string", nullable=true, length=200)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $logradouro;

    /**
     *
     * @ORM\Column(name="numero", type="string", nullable=true, length=200)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $numero;

    /**
     *
     * @ORM\Column(name="complemento", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $complemento;

    /**
     *
     * @ORM\Column(name="bairro", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $bairro;

    /**
     *
     * @ORM\Column(name="cidade", type="string", nullable=true, length=120)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $cidade;

    /**
     *
     * @ORM\Column(name="estado", type="string", nullable=true, length=2)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $estado;

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
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $obs;

    /**
     * @return string|null
     */
    public function getNome(): ?string
    {
        return $this->nome;
    }

    /**
     * @param string|null $nome
     * @return Agencia
     */
    public function setNome(?string $nome): Agencia
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string|null $email
     * @return Agencia
     */
    public function setEmail(?string $email): Agencia
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getPercComissao(): ?float
    {
        return $this->percComissao;
    }

    /**
     * @param float|null $percComissao
     * @return Agencia
     */
    public function setPercComissao(?float $percComissao): Agencia
    {
        $this->percComissao = $percComissao;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCep(): ?string
    {
        return $this->cep;
    }

    /**
     * @param string|null $cep
     * @return Agencia
     */
    public function setCep(?string $cep): Agencia
    {
        $this->cep = $cep;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    /**
     * @param string|null $logradouro
     * @return Agencia
     */
    public function setLogradouro(?string $logradouro): Agencia
    {
        $this->logradouro = $logradouro;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNumero(): ?string
    {
        return $this->numero;
    }

    /**
     * @param string|null $numero
     * @return Agencia
     */
    public function setNumero(?string $numero): Agencia
    {
        $this->numero = $numero;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    /**
     * @param string|null $complemento
     * @return Agencia
     */
    public function setComplemento(?string $complemento): Agencia
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBairro(): ?string
    {
        return $this->bairro;
    }

    /**
     * @param string|null $bairro
     * @return Agencia
     */
    public function setBairro(?string $bairro): Agencia
    {
        $this->bairro = $bairro;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCidade(): ?string
    {
        return $this->cidade;
    }

    /**
     * @param string|null $cidade
     * @return Agencia
     */
    public function setCidade(?string $cidade): Agencia
    {
        $this->cidade = $cidade;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEstado(): ?string
    {
        return $this->estado;
    }

    /**
     * @param string|null $estado
     * @return Agencia
     */
    public function setEstado(?string $estado): Agencia
    {
        $this->estado = $estado;
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
     * @return Agencia
     */
    public function setFoneFixo(?string $foneFixo): Agencia
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
     * @return Agencia
     */
    public function setFoneCelular(?string $foneCelular): Agencia
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
     * @return Agencia
     */
    public function setFoneWhatsapp(?string $foneWhatsapp): Agencia
    {
        $this->foneWhatsapp = $foneWhatsapp;
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
     * @return Agencia
     */
    public function setObs(?string $obs): Agencia
    {
        $this->obs = $obs;
        return $this;
    }


}

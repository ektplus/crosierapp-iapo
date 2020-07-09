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
 * @ORM\Table(name="rtr_tur_passageiro")
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
    private $viagem;


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
     * @ORM\Column(name="dt_nascimento", type="datetime", nullable=true)
     * @var \DateTime|null
     *
     * @Groups("entity")
     */
    private $dtNascimento;

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
     * @ORM\Column(name="email", type="string", nullable=true)
     * @var string|null
     *
     * @Groups("entity")
     */
    private $email;

    /**
     *
     * @ORM\Column(name="obs", type="string", nullable=false)
     * @Groups("entity")
     *
     * @var string|null
     */
    private $obs;

    /**
     * @return Viagem|null
     */
    public function getViagem(): ?Viagem
    {
        return $this->viagem;
    }

    /**
     * @param Viagem|null $viagem
     * @return Passageiro
     */
    public function setViagem(?Viagem $viagem): Passageiro
    {
        $this->viagem = $viagem;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCpf(): ?string
    {
        return StringUtils::mascararCnpjCpf($this->cpf);
    }

    /**
     * @param string|null $cpf
     * @return Passageiro
     */
    public function setCpf(?string $cpf): Passageiro
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
     * @return Passageiro
     */
    public function setRg(?string $rg): Passageiro
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
     * @return Passageiro
     */
    public function setNome(?string $nome): Passageiro
    {
        $this->nome = $nome;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDtNascimento(): ?\DateTime
    {
        return $this->dtNascimento;
    }

    /**
     * @param \DateTime|null $dtNascimento
     * @return Passageiro
     */
    public function setDtNascimento(?\DateTime $dtNascimento): Passageiro
    {
        $this->dtNascimento = $dtNascimento;
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
     * @return Passageiro
     */
    public function setFoneFixo(?string $foneFixo): Passageiro
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
     * @return Passageiro
     */
    public function setFoneCelular(?string $foneCelular): Passageiro
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
     * @return Passageiro
     */
    public function setFoneWhatsapp(?string $foneWhatsapp): Passageiro
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
     * @return Passageiro
     */
    public function setFoneRecados(?string $foneRecados): Passageiro
    {
        $this->foneRecados = $foneRecados;
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
     * @return Passageiro
     */
    public function setEmail(?string $email): Passageiro
    {
        $this->email = $email;
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
     * @return Passageiro
     */
    public function setObs(?string $obs): Passageiro
    {
        $this->obs = $obs;
        return $this;
    }


}

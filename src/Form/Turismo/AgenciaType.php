<?php

namespace App\Form\Turismo;

use App\Entity\Turismo\Agencia;
use CrosierSource\CrosierLibBaseBundle\Entity\Security\User;
use CrosierSource\CrosierLibBaseBundle\Repository\Security\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class AgenciaType extends AbstractType
{

    private EntityManagerInterface $doctrine;

    /**
     * @required
     * @param EntityManagerInterface $doctrine
     */
    public function setDoctrine(EntityManagerInterface $doctrine): void
    {
        $this->doctrine = $doctrine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder->add('nome', TextType::class, [
            'label' => 'Nome',
            'attr' => ['class' => 'focusOnReady']
        ]);

        $builder->add('email', TextType::class, [
            'label' => 'E-mail',
            'required' => false,
            'attr' => ['class' => 'email']
        ]);

        $builder->add('percComissao', PercentType::class, [
            'label' => 'Comissão',
            'scale' => 2,
            'required' => false,
            'attr' => ['class' => 'crsr-dec2']
        ]);

        $builder->add('foneFixo', TextType::class, [
            'label' => 'Fone (Fixo)',
            'required' => false,
            'attr' => ['class' => 'telefone']
        ]);

        $builder->add('foneCelular', TextType::class, [
            'label' => 'Fone (Celular)',
            'required' => false,
            'attr' => ['class' => 'telefone']
        ]);

        $builder->add('foneWhatsapp', TextType::class, [
            'label' => 'Fone (Whatsapp)',
            'required' => false,
            'attr' => ['class' => 'telefone']
        ]);

        $builder->add('cep', TextType::class, [
            'label' => 'CEP',
            'attr' => [
                'class' => 'cepComBtnConsulta',
                'data-prefixodoscampos' => 'agencia_'
            ],
            'required' => false
        ]);

        $builder->add('logradouro', TextType::class, [
            'label' => 'Logradouro',
            'required' => false
        ]);

        $builder->add('numero', TextType::class, [
            'label' => 'Número',
            'required' => false
        ]);

        $builder->add('complemento', TextType::class, [
            'label' => 'Complemento',
            'required' => false
        ]);

        $builder->add('bairro', TextType::class, [
            'label' => 'Bairro',
            'required' => false
        ]);

        $builder->add('cidade', TextType::class, [
            'label' => 'Cidade',
            'required' => false
        ]);


        $builder->add('estado', ChoiceType::class, [
            'label' => 'Estado',
            'choices' => [
                'Acre' => 'AC',
                'Alagoas' => 'AL',
                'Amapá' => 'AP',
                'Amazonas' => 'AM',
                'Bahia' => 'BA',
                'Ceará' => 'CE',
                'Distrito Federal' => 'DF',
                'Espírito Santo' => 'ES',
                'Goiás' => 'GO',
                'Maranhão' => 'MA',
                'Mato Grosso' => 'MT',
                'Mato Grosso do Sul' => 'MS',
                'Minas Gerais' => 'MG',
                'Pará' => 'PA',
                'Paraíba' => 'PB',
                'Paraná' => 'PR',
                'Pernambuco' => 'PE',
                'Piauí' => 'PI',
                'Rio de Janeiro' => 'RJ',
                'Rio Grande do Norte' => 'RN',
                'Rio Grande do Sul' => 'RS',
                'Rondônia' => 'RO',
                'Roraima' => 'RR',
                'Santa Catarina' => 'SC',
                'São Paulo' => 'SP',
                'Sergipe' => 'SE',
                'Tocantins' => 'TO'
            ],
            'required' => false
        ]);


        $builder->add('obs', TextareaType::class, [
            'label' => 'Obs',
            'required' => false
        ]);

        /** @var UserRepository $repoUser */
        $repoUser = $this->doctrine->getRepository(User::class);
        $builder->add('user', EntityType::class, [
            'label' => 'Usuário',
            'class' => User::class,
            'choice_label' => 'username',
            'choices' => $repoUser->findAll(['username' => 'ASC']),
            'attr' => [
                'class' => 'autoSelect2'
            ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Agencia::class
        ));
    }
}
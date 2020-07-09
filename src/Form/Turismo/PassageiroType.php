<?php

namespace App\Form\Turismo;

use App\Entity\Turismo\Passageiro;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class PassageiroType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('id', HiddenType::class, [
            'required' => false
        ]);

        $builder->add('cpf', TextType::class, [
            'label' => 'CPF',
            'attr' => ['class' => 'cpf focusOnReady']
        ]);

        $builder->add('rg', TextType::class, [
            'label' => 'RG',
        ]);

        $builder->add('nome', TextType::class, [
            'label' => 'Nome',
        ]);

        $builder->add('dtNascimento', DateType::class, [
            'label' => 'Dt Nascimento',
            'widget' => 'single_text',
            'required' => true,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ]);

        $builder->add('email', TextType::class, [
            'label' => 'E-mail',
            'required' => false,
            'attr' => ['class' => 'email']
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

        $builder->add('foneRecados', TextType::class, [
            'label' => 'Fone (Recados)',
            'required' => false,
            'attr' => ['class' => 'telefone']
        ]);


        $builder->add('obs', TextareaType::class, [
            'label' => 'Obs',
            'required' => false
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Passageiro::class
        ));
    }
}
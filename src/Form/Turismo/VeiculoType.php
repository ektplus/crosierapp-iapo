<?php

namespace App\Form\Turismo;

use App\Entity\Turismo\Veiculo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class VeiculoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('prefixo', TextType::class, [
            'label' => 'Prefixo',
            'attr' => ['class' => 'focusOnReady']
        ]);

        $builder->add('apelido', TextType::class, [
            'label' => 'Apelido',
            'required' => false,
        ]);

        $builder->add('placa', TextType::class, [
            'label' => 'Placa',
            'required' => false,
        ]);

        $builder->add('status', TextType::class, [
            'label' => 'Status',
            'required' => false,
        ]);

        $builder->add('croqui', ChoiceType::class, [
            'label' => 'Croqui',
            'choices' => [
                '3740-3730' => '3740-3730',
                '32XX' => '32XX',
                '2301' => '2301',
                '2400' => '2400',
                '2500' => '2500',
                '2501' => '2501',
                '2502' => '2502',
                '2600' => '2600',
                '2900' => '2900',
                '3010' => '3010',
                '3030' => '3030',
                '3100' => '3100',
                '3600' => '3600',
                '3601' => '3601',
                '3602' => '3602',
                '3700' => '3700',
            ],
            'attr' => ['class' => 'autoSelect2']
        ]);

        $builder->add('renavan', TextType::class, [
            'label' => 'Renavan',
        ]);

        $builder->add('dtVenctoDer', DateType::class, array(
            'label' => 'Dt Vencto DER',
            'widget' => 'single_text',
            'required' => true,
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ));

        $builder->add('dtVenctoAntt', DateType::class, array(
            'label' => 'Dt Vencto ANTT',
            'widget' => 'single_text',
            'required' => true,
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ));

        $builder->add('dtVenctoTacografo', DateType::class, array(
            'label' => 'Dt Vencto Tacógrafo',
            'widget' => 'single_text',
            'required' => true,
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ));

        $builder->add('dtVenctoSeguro', DateType::class, array(
            'label' => 'Dt Vencto Seguro',
            'widget' => 'single_text',
            'required' => true,
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ));

        $builder->add('obs', TextareaType::class, [
            'label' => 'Obs',
            'required' => false,
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Veiculo::class
        ));
    }
}
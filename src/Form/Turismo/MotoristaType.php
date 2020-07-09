<?php

namespace App\Form\Turismo;

use App\Entity\Turismo\Motorista;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class MotoristaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('cpf', TextType::class, [
            'label' => 'CPF',
            'attr' => ['class' => 'cpf focusOnReady']
        ]);

        $builder->add('rg', TextType::class, [
            'label' => 'RG',
        ]);

        $builder->add('Nome', TextType::class, [
            'label' => 'Nome',
        ]);

        $builder->add('cnh', TextType::class, [
            'label' => 'CNH',
        ]);

        $builder->add('dtVenctoCnh', DateType::class, array(
            'label' => 'Dt Vencto CNH',
            'widget' => 'single_text',
            'required' => true,
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ));

        $builder->add('dtVenctoCarteiraSaude', DateType::class, array(
            'label' => 'Dt Vencto Carteira Saúde',
            'widget' => 'single_text',
            'required' => true,
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ));

        $builder->add('dtValidadeCursoTranspPassag', DateType::class, array(
            'label' => 'Dt Vencto Curso Transporte de Passageiros',
            'widget' => 'single_text',
            'required' => true,
            'html5' => false,
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'crsr-date'
            ]
        ));

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
            'data_class' => Motorista::class
        ));
    }
}
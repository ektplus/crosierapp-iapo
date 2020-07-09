<?php

namespace App\Form\Turismo;

use App\Entity\Turismo\Itinerario;
use App\Entity\Turismo\Veiculo;
use App\Repository\Turismo\VeiculoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class ItinerarioType extends AbstractType
{

    /** @var EntityManagerInterface */
    private $doctrine;

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

        $builder->add('origemCidade', TextType::class, [
            'label' => 'Cidade (Origem)',
            'required' => false,
            'attr' => ['class' => 'focusOnReady']
        ]);

        $builder->add('origemEstado', ChoiceType::class, [
            'label' => 'Estado (Origem)',
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
            'required' => false,
            'attr' => ['class' => 'autoSelect2']
        ]);


        $builder->add('destinoCidade', TextType::class, [
            'label' => 'Cidade (Destino)',
            'required' => false
        ]);

        $builder->add('destinoEstado', ChoiceType::class, [
            'label' => 'Estado (Destino)',
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
            'required' => false,
            'attr' => ['class' => 'autoSelect2']
        ]);

        /** @var VeiculoRepository $repoVeiculo */
        $repoVeiculo = $this->doctrine->getRepository(Veiculo::class);
        $builder->add('veiculo', EntityType::class, [
            'label' => 'Veículo',
            'class' => Veiculo::class,
            'choice_label' => function (?Veiculo $veiculo) {
                return $veiculo ? $veiculo->getApelido() : null;
            },
            'choices' => $repoVeiculo->findAll(['apelido' => 'ASC']),
            'attr' => [
                'class' => 'autoSelect2'
            ],
            'required' => false
        ]);

        $builder->add('precoMin', MoneyType::class, [
            'label' => 'Preço (Mín)',
            'currency' => 'BRL',
            'grouping' => 'true',
            'attr' => [
                'class' => 'crsr-money'
            ],
            'required' => false
        ]);

        $builder->add('precoMax', MoneyType::class, [
            'label' => 'Preço (Máx)',
            'currency' => 'BRL',
            'grouping' => 'true',
            'attr' => [
                'class' => 'crsr-money'
            ],
            'required' => false
        ]);

        $builder->add('obs', TextareaType::class, [
            'label' => 'Obs',
            'required' => false
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Itinerario::class
        ));
    }
}
<?php

namespace App\Form\Turismo;

use App\Entity\Turismo\Agencia;
use App\Entity\Turismo\Itinerario;
use App\Entity\Turismo\Motorista;
use App\Entity\Turismo\Veiculo;
use App\Entity\Turismo\Viagem;
use App\Repository\Turismo\AgenciaRepository;
use App\Repository\Turismo\ItinerarioRepository;
use App\Repository\Turismo\MotoristaRepository;
use App\Repository\Turismo\VeiculoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @author Carlos Eduardo Pauluk
 */
class ViagemType extends AbstractType
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


        $builder->add('pedido', TextType::class, [
            'label' => 'Pedido',
            'attr' => ['class' => 'focusOnReady']
        ]);

        $builder->add('dtHrSaida', DateTimeType::class, [
            'label' => 'Dt/Hr Saída',
            'widget' => 'single_text',
            'html5' => false,
            'required' => true,
            'format' => 'dd/MM/yyyy HH:mm:ss',
            'help' => 'Informar com dd/mm/aaaa hh:mm:ss',
            'attr' => [
                'class' => 'crsr-datetime'
            ]
        ]);

        $builder->add('dtHrRetorno', DateTimeType::class, [
            'label' => 'Dt/Hr Retorno',
            'widget' => 'single_text',
            'required' => true,
            'format' => 'dd/MM/yyyy HH:mm:ss',
            'html5' => false,
            'help' => 'Informar com dd/mm/aaaa hh:mm:ss',
            'attr' => [
                'class' => 'crsr-datetime'
            ]
        ]);

        /** @var ItinerarioRepository $repoItinerario */
        $repoItinerario = $this->doctrine->getRepository(Itinerario::class);
        $builder->add('itinerario', EntityType::class, [
            'label' => 'Itinerário',
            'class' => Itinerario::class,
            'choice_label' => function (?Itinerario $itinerario) {
                return $itinerario ? $itinerario->getDescricaoMontada() : null;
            },
            'choices' => $repoItinerario->findAll(['origemCidade' => 'ASC']),
            'attr' => ['class' => 'autoSelect2'],
            'required' => true
        ]);

        /** @var VeiculoRepository $repoVeiculo */
        $repoVeiculo = $this->doctrine->getRepository(Veiculo::class);
        $builder->add('veiculo', EntityType::class, [
            'label' => 'Veículo',
            'class' => Veiculo::class,
            'choice_label' => function (?Veiculo $veiculo) {
                return $veiculo ? $veiculo->apelido : null;
            },
            'choices' => $repoVeiculo->findAll(['apelido' => 'ASC']),
            'attr' => [
                'class' => 'autoSelect2'
            ],
        ]);

        $builder->add('flagFinanceiro', ChoiceType::class, [
            'label' => 'Financeiro',
            'choices' => [
                'Sim' => true,
                'Não' => false
            ],
            'attr' => ['class' => 'autoSelect2'],
        ]);

        $builder->add('flagContrato', ChoiceType::class, [
            'label' => 'Contrato',
            'choices' => [
                'Sim' => true,
                'Não' => false
            ],
            'attr' => ['class' => 'autoSelect2'],
        ]);

        $builder->add('valor', MoneyType::class, [
            'label' => 'Valor',
            'currency' => 'BRL',
            'grouping' => 'true',
            'attr' => [
                'class' => 'crsr-money'
            ],
            'required' => false,
            'help' => 'Custo total da viagem',
        ]);

        /** @var AgenciaRepository $repoAgencia */
        $repoAgencia = $this->doctrine->getRepository(Agencia::class);
        $builder->add('agencia', EntityType::class, [
            'label' => 'Agência',
            'class' => Agencia::class,
            'choice_label' => function (?Agencia $agencia) {
                return $agencia ? $agencia->nome : null;
            },
            'choices' => $repoAgencia->findAll(['nome' => 'ASC']),
            'attr' => [
                'class' => 'autoSelect2'
            ],
        ]);

        /** @var MotoristaRepository $repoMotorista */
        $repoMotorista = $this->doctrine->getRepository(Motorista::class);
        $builder->add('motorista', EntityType::class, [
            'label' => 'Motorista',
            'class' => Motorista::class,
            'choice_label' => function (?Motorista $motorista) {
                return $motorista ? $motorista->nome : null;
            },
            'choices' => $repoMotorista->findAll(['nome' => 'ASC']),
            'attr' => [
                'class' => 'autoSelect2'
            ],
            'required' => true
        ]);

        $builder->add('status', ChoiceType::class, [
            'label' => 'Status',
            'choices' => [
                'NOVA' => 'NOVA',
                'PROGRAMADA' => 'PROGRAMADA',
                'CANCELADA' => 'CANCELADA',
            ],
            'attr' => ['class' => 'autoSelect2']
        ]);

        $builder->add('obs', TextareaType::class, [
            'label' => 'Obs',
            'required' => false,
            'attr' => [
                'class' => 'summernote'
            ]
        ]);

        $builder->add('valorPoltrona', MoneyType::class, [
            'label' => 'Valor Poltrona',
            'currency' => 'BRL',
            'grouping' => 'true',
            'attr' => [
                'class' => 'crsr-money'
            ],
            'required' => false
        ]);

        $builder->add('valorTaxas', MoneyType::class, [
            'label' => 'Valor Taxas',
            'currency' => 'BRL',
            'grouping' => 'true',
            'attr' => [
                'class' => 'crsr-money'
            ],
            'required' => false
        ]);

        $builder->add('valorBagagem', MoneyType::class, [
            'label' => 'Valor Bagagem',
            'currency' => 'BRL',
            'grouping' => 'true',
            'attr' => [
                'class' => 'crsr-money'
            ],
            'required' => false
        ]);

        $builder->add('valorEscolhaPoltrona', MoneyType::class, [
            'label' => 'Valor Escolha Poltrona',
            'currency' => 'BRL',
            'grouping' => 'true',
            'attr' => [
                'class' => 'crsr-money'
            ],
            'required' => false
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Viagem::class
        ));
    }
}
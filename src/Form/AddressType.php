<?php

namespace App\Form;

use App\Entity\Address;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',\Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'label'=>'quelle nom souhaitez vous donner a votre adrresse?',
                'attr'=>[
                    'placeholder'=>'Nommez votre adresse'
                ]
            ])
            ->add('adresse',\Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'label'=>'entrer votre adresse ',
                'attr'=>[
                    'placeholder'=>'12 rue ....'
                ]
            ])
            ->add('phone',NumberType::class,[
            'label'=>'Votre telephone',
                'attr'=>[
        'placeholder'=>'Entrer votre numéro de téléphone ... '
    ]
            ])
            ->add('pays',CountryType::class,[
                'label'=>'pays',

            ])
            ->add('city',\Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>'Entrez votre ville'
                ]
            ])
            ->add('postal',\Symfony\Component\Form\Extension\Core\Type\TextType::class,[
                'label'=>'Votre code postal',
                'attr'=>[
                    'placeholder'=>'Entrer votre code postal'
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'Valider',
                'attr'=>[
                    'class'=>'btn btn-danger'
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Transporteurs;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user =$options['user'];
        $builder
            ->add('addresses',EntityType::class,[
                'label'=>'choisissez votre addresse de livraison',
                'required'=>true,
                'class'=>Address::class,

                'expanded'=>true,
                'multiple'=>false,
                'choices'=>$user->getAddresses()
            ])
            ->add('Transporteurs',EntityType::class,[
                'label'=>'choisissez votre transporteur',
                'required'=>true,
                'class'=>Transporteurs::class,
                'expanded'=>true,
                'multiple'=>false,

            ])
            ->add('submit',SubmitType::class,[
                'label'=>'valider ma commande',
                'attr'=>[
                    'class'=>'btn btn-danger btn-block'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
          'user'=>array()
        ]);
    }
}

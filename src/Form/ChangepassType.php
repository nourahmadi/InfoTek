<?php

namespace App\Form;

use App\Entity\User1;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangepassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
                'disabled'=>true
            ])
            ->add('old_password',PasswordType::class,[
                'label'=>'mon mot de passe actuelle',
                'attr'=>[
                    'placeholder'=>'veuillez saisir votre mot de passe actuel'
                ],
                'required'=>true,
                'mapped'=>false
            ])
            ->add('new_password',PasswordType::class,[
                'label'=>'mon nouveau mot de passe',
                'attr'=>[
                    'placeholder'=>'veuillez saisir votre nouveau mot de passe '
                ],
                'required'=>true,
                'mapped'=>false
            ])
            ->add('firstname',TextType::class,[
                'disabled'=>true
            ])
            ->add('lastname',TextType::class,[
        'disabled'=>true
    ])
            ->add('submit',SubmitType::class,[
                'label' => 'modifier'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User1::class,
        ]);
    }
}

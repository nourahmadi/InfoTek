<?php

namespace  App\Form;

use App\classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string',TextType::class,[
                'label'=>false,
                'required'=>false,
                'attr'=>[
                  'placeholder'=>'votre recherche ....'
                       ],

                ])
            ->add('categories',EntityType::class,[
                'label'=>false,
                'required'=>false,
                'class'=>Category::class,
                'multiple'=>true,
                'expanded'=>true
            ])

            ->add('submit',SubmitType::class,[
                'label' => 'rechercher',
                'attr'  =>[
                    'class'=>'btn-block btn-dark'
                ]
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {

        $resolver->setDefaults([
            'data_class' => Search::class,
            'method'=>'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ]);
    }
    public function  getBlockPrefix()
    {
        return '';
    }

}
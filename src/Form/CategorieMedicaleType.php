<?php

namespace App\Form;

use App\Entity\CategorieMedicale;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieMedicaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['help' => 'Veuillez saisir le nom de la catégorie à ajouter',
                'attr' =>['class'=>'form-control',
                    'label'=>"Nom de la catégorie",
                    'placeholder'=>"saisir le nom de la catégorie...",
                    'rows'=>"1",
                    'inputType'=>'text',


                ],



            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CategorieMedicale::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DelegueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numtel')
            ->add('adresse',ChoiceType::class,
                array('choices'=>array(
                    'Tunis'=>'Tunis',
                    'Ariana'=>'Ariana',
                    'Ben arous'=>'Ben arous',
                    'Sousse'=>'Sousse',
                    'Sfax'=>'Sfax',
                    'Monastir'=>'Monastir',
                    'Nabeul'=>'Nabeul',
                    'Mahdia'=>'Mahdia',
                    'Kairaoun'=>'Kairouan',
                    'Bizerte'=>'Bizerte',
                    'Mednine'=>'Mednine',
                    'Manouba'=>'Manouba',
                    'Gabes'=>'Gabes',
                    'Gafsa'=>'Gafsa',
                    'Jendouba'=>'Jendouba',
                    'Le kef'=>'Le kef',
                    'Sidi bouzid'=>'Sidi bouzid',
                    'Kasserine'=>'Kasserine',
                    'Seliana'=>'Seliana',
                    'Kebili'=>'Kebili',
                    'Tataouine'=>'Tataouine',
                    'Djerbe'=>'Djerba',
                    'Tozeur'=>'Tozeur'
                ) ))
            ->add('cin')
            ->add('societe')
            ->add('image',FileType::class,
                ['label' => false,
                    'multiple' => false,
                    'mapped' => false,
                    'required' => false])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

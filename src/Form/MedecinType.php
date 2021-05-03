<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MedecinType extends AbstractType
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
            ->add('matricule')
            ->add('cnam')
            ->add('cnss')
            ->add('specialite',ChoiceType::class,
                array('choices'=>array(
                    'Cardiologue'=>'Cardiologue',
                    'chirurgien Esthétique'=>'chirurgien Esthétique',
                    'Dentiste'=>'Dentiste',
                    'Darmatologue'=>'Darmatologue',
                    'Généraliste'=>'Généraliste',
                    'Gynécologue'=>'Gynécologue',
                    'Neurologue'=>'Neurologue',
                    'Ophtalmologue'=>'Ophtalmologue',
                    'Psychiatre'=>'Psychiatre',
                    'Sexologue'=>'Sexologue',
                    'Psychothérapeute'=>'Psychothérapeute',
                    'Pédiatre'=>'Pédiatre'
                ) ))
            ->add('disponabilite', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'])
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

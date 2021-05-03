<?php

namespace App\Form;

use App\Entity\CategorieService;
use App\Entity\FourniseurService;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FournisseurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fourniseur',TextType::class,[
            'attr' => ['class' => 'form-control']
            ])
            ->add('contact',EmailType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('numero',TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('gouvernorat',ChoiceType::class, [
                'choices' => [
                    'Ariana' =>'Ariana',
                    'Béja'	 =>'Béja',
                    'Ben Arous' =>	'Ben Arous',
                    'Bizerte' =>	'Bizerte',
                    'Gabès'	 =>'Gabès',
                    'Gafsa'	 =>'Gafsa',
                    'Jendouba' =>	'Jendouba',
                    'Kairouan' =>	'Kairouan',
                    'Kasserine' =>	'Kasserine',
                    'Kébili' =>'Kébili',
                    'Le Kef' =>'Le Kef',
                    'Mahdia' =>	'Mahdia',
                    'La Manouba' =>	'La Manouba',
                    'Médenine' =>	'Médenine',
                    'Monastir' =>	'Monastir',
                    'Nabeul' =>'Nabeul',
                    'Sfax'	 =>'Sfax',
                    'Sidi Bouzid' =>'Sidi Bouzid',
                    'Siliana' =>'Siliana' ,
                    'Sousse' =>'Sousse',
                    'Tataouine' =>	'Tataouine',
                    'Tozeur' =>'Tozeur',
                    'Tunis'	 =>'Tunis'	,
                    'Zaghouan' =>'Zaghouan',

                ],
            ])
            ->add('maplocation')
            ->add('service',EntityType::class,['class'=>Service::class,'choice_label'=>'libelle','choice_value'=>'id'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FourniseurService::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\CategorieService;
use App\Entity\FourniseurService;
use App\Entity\Service;
use App\Repository\FourniseurServiceRepository;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle',TextType::class,[
                'attr' => ['class' => 'form-control']
            ])
            ->add('categorie',EntityType::class,[
                'class'=>CategorieService::class,
                'choice_label'=>'libelle',
                'choice_value'=>'id',
                'placeholder'=>'selectionnez le catégorie du service'

               ])

            ->add('description',TextareaType::class)
            ->add('prix',MoneyType::class, [
                "currency" => "TND",
                "error_bubbling" => true
            ])
            ->add('disponibilite',ChoiceType::class,[

                'choices'=>[
                    'oui'=>true ,
                    'non'=>false
                ]

            ])
          ;
        }




    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}

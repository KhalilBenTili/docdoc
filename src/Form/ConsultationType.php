<?php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class ConsultationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('datehr')

         /*   ->add('userM', EntityType::class, array(
                'choice_label' => function ($userM) {
                    return $userM->getNom() . ' ' . $userM->getPrenom();
                },
                'class'=>User::class,
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('u')->where('u.type LIKE :medecin ')
                            ->setParameter('medecin', "medecin")
                            ->orderBy('u.id', 'DESC');
                },
            ))*/

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}

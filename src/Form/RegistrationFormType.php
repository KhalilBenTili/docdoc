<?php

namespace App\Form;

use App\Entity\User;
use Grafikart\RecaptchaBundle\Type\RecaptchaSubmitType;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('dnaissance', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'])
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
            ->add('numtel')
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('type',ChoiceType::class,
                array('choices'=>array(
                    'patient'=>'patient',
                    'medecin'=>'medecin',
                    'pharmacien'=>'pharmacien',
                    'delegue'=>'delegue'
                ) ))
            ->add('captcha',CaptchaType::class)




           /* ->add('role', ChoiceType::class, [
                'choices' => [
                    'Patient' => '1',
                    'Medecin' => '2',
                    'Pharamacien' => '3',
                    'Delegue' => '4'

                ],'multiple'=>false,'expanded'=>true])
            ,'choice_label' => function($choice){
             if('2' == $choice) {

                     ->add('cinrecto')
                     ->add('cinverso')
                     ->add('matricule')
                     ->add('cnss')
                     ->add('cnam')  ;
                 return 'merci';
             }
        if('3' == $choice){

                ->add('cinrecto')
                ->add('cinverso')
                ->add('matricule')
                ->add('cnss')
                ->add('cnam')
                return 'merci';
             }
        if('4' == $choice){

                ->add('cinrecto')
                ->add('cinverso')
                ->add('societe')
                return 'merci';

             }*/



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

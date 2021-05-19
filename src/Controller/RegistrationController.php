<?php

namespace App\Controller;

use App\Entity\Roles;
use App\Entity\User;
use App\Form\DelegueType;
use App\Form\ImageType;
use App\Form\MedecinType;
use App\Form\PatientType;
use App\Form\PharamacienType;
use App\Form\ReclamationType;
use App\Form\RegistrationFormType;
use App\Form\RoleType;
use App\Repository\UserRepository;
use App\Security\AppUserAuthenticator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class RegistrationController extends AbstractController
{


    /**
     * @Route("/inscription", name="app_register")
     */
    public function register(UserRepository $repo, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, AppUserAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            if ($user->getType() == "patient") {
                return $this->redirectToRoute("app_login");
            } elseif ($user->getType() == "medecin") {
                return $this->redirectToRoute("accueil");

            } elseif ($user->getType() == "pharmacien") {
                return $this->redirectToRoute("accueil");
            } else {
                return $this->redirectToRoute("accueil");
            }
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }


        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addUserJson/new",name="addUser")
     */
    public function addUserJSON(Request $request,NormalizerInterface $normalizer,UserPasswordEncoderInterface $passwordEncoder, \Swift_Mailer $mailer){
        $em = $this->getDoctrine()->getManager();
        $user= new User();
        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $email=$request->query->get('email');
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            return new Response("email invalid");
        }
        $user->setEmail($email);
        $password = $request->query->get('password');
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );
        // $user->setDnaissance(new \DateTime($request->get('dnaissance')));
        $user->setAdresse($request->get('adresse'));
        $user->setNumtel($request->get('numtel'));
        $user->setType($request->get('type'));
        $message = (new \Swift_Message('BIENVENU !'))
            ->setFrom('docdocpidev@gmail.com')
            ->setTo($request->get('email'))
            ->setBody(
                $this->renderView(
                // templates/emails/registration.html.twig
                    'emails/NVUSER.html.twig'), 'text/html')

            // you can remove the following code if you don't define a text version for your emails
            ->addPart(
                $this->renderView(
                // templates/emails/registration.txt.twig
                    'emails/nouvprod.txt.twig'
                ),
                'text/plain'
            )
        ;

        $mailer->send($message);

        $em->persist($user);
        $em->flush();
//        $message = (new \Swift_Message('BIENVENU !'))
//            ->setFrom('docdocpidev@gmail.com','DocDoc')
//            ->setTo($user->getEmail())
//            ->setBody('Bonjour Madame/Monsieur, c est avec plaisir qu on vous écrit pour vous remercier sincérement d avoir choisi de faire confiance à DOCDOC.');
//        $mailer->send($message);
        $jsonContent = $normalizer->normalize($user,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
     * @param NormalizerInterface $normalizer
     * @param UserRepository $repo
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route ("/afficheusermob/",name="affuMobile")
     */
    public function afficheUser(NormalizerInterface $normalizer, UserRepository $repo){
        $user=$repo->findBy(array('type'=>'medecin'));


        $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));

    }

    /**
     * @param NormalizerInterface $normalizer
     * @param UserRepository $rep
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route("/affpharmobile/",name="affphmob")
     */
    public function affpharmacien(NormalizerInterface $normalizer,UserRepository $rep){
        $user=$rep->findBy(array('type'=>'pharmacien'));
        $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("userinterface",name="userinterface")
     */
    public function userinetrface(UserRepository $repo)
    {
        $user = $repo->findAll();
        return $this->render("registration/UserInterface.html.twig", ['user' => $user]);
    }



    /**
     * @param UserRepository $repo
     * @param $id
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *@Route("/update/{id}",name="ModifierUser")
     */
    function UpdateUser(UserRepository $repo,$id,Request $req){
        $user=$repo->find($id);
        if ($user->getType() == "medecin") {
            $form=$this->createForm(MedecinType::class,$user);
            $form->add('modifier',SubmitType::class);
            $form->handleRequest($req);
            if($form->isSubmitted() ){
                $em=$this->getDoctrine()->getManager();
                $em->flush();
                return $this->redirectToRoute('userinterface');
            }
            return $this->render("registration/medecin.html.twig", ['form' => $form->createView()]);}
        elseif ($user->getType() == "patient") {
            $form = $this->createForm(PatientType::class, $user);
            /*$form->add('confirmer', SubmitType::class);*/
            $form->handleRequest($req);
            if ($form->isSubmitted()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->redirectToRoute('userinterface');

            }
            return $this->render("registration/Patient.html.twig", ['form' => $form->createView()]);
        }
        elseif ($user->getType() == "pharmacien") {

            $form = $this->createForm(PharamacienType::class, $user);
            /*   $form->add('confirmer', SubmitType::class);*/
            $form->handleRequest($req);
            if ($form->isSubmitted() ) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('userinterface');
            }
            return $this->render("registration/pharmacien.html.twig", ['form' => $form->createView()]);
        }
        elseif ($user->getType() == "delegue"){
            $form=$this->createForm(DelegueType::class,$user);
            /*  $form->add('confirmer',SubmitType::class);*/
            $form->handleRequest($req);
            if($form->isSubmitted() ) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();
                return $this->redirectToRoute('userinterface');
            }
            return $this->render("registration/delegue.html.twig",['form'=>$form->createView()]);
        }

    }

    /**
     * @param Request $req
     * @param $id
     * @param UserRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/UpdateImage/{id}", name="updateimage")
     */
    function AjoutImage(Request $req,$id,UserRepository $repo){
        $user=$repo->find($id);
        $form=$this->createForm(ImageType::class,$user);
        $form->add('Ajouter une photo',SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $file = $user->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e){
                // ...handle exception if something happens during file upload
            }
            $em=$this->getDoctrine()->getManager();
            $user->setImage($fileName);
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('userinterface');
        }
        return $this->render("registration/Image.html.twig", ['form' => $form->createView()]);
    }




}


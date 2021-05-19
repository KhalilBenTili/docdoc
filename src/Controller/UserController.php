<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ContactType;
use App\Form\EditUserType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin", name="user")
     */
    public function index(): Response
    {
        return $this->render('baseBack.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    /**
     * @Route("/user", name="userr")
     */
    public function index1(): Response
    {
        return $this->render('base.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichMedecin",name="AfficheMedecin")
     */
    function affichemedForAdmin(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AfficheMedecin.html.twig",
        ['user'=>$user]);
    }
    /**
     * @Route("/admin/AffichMedecinajax",name="AfficheMedecinajax")
     */
    function afficheMedecinForajax(UserRepository $repository,Request $request){
        $requestString=$request->get('searchValue');
        $user = $repository->findUserByname($requestString);
        return $this->render('user/UserAjaxBack.html.twig' ,[
            "user"=>$user,
        ]);
    }


    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichDelegue",name="AfficheDelegue")
     */
    function affichedel(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AfficheDelegue.html.twig",
            ['user'=>$user]);
    }
    /**
     * @Route("/admin/AffichDelegueajax",name="AfficheDelegueajax")
     */
    function afficheDelegueForajax(UserRepository $repository,Request $request){
        $requestString=$request->get('searchValue');
        $user = $repository->findUserByname($requestString);
        return $this->render('user/DelegueAjaxBack.html.twig' ,[
            "user"=>$user,
        ]);
    }
    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichPatient",name="AffichePatient")
     */
    function affichepat(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AffichePatient.html.twig",
            ['user'=>$user]);
    }
    /**
     * @Route("/admin/AffichPatientajax",name="AffichePatientajax")
     */
    function affichePatientForajax(UserRepository $repository,Request $request){
        $requestString=$request->get('searchValue');
        $user = $repository->findUserByname($requestString);
        return $this->render('user/PatientAjaxBack.html.twig' ,[
            "user"=>$user,
        ]);
    }

    /**
     * @param UserRepository $repo
     * @param $id
     * @return Response
     * @Route("/admin/AffichUser/{id}",name="Detailp")
     */
    function affichepatdet(UserRepository $repo,$id){

        $user=$repo->find($id);

        return $this->render("user/AffichePatdel.html.twig",
            ['user'=>$user]);
    }
    /**
     * @param UserRepository $repo
     * @param $id
     * @return Response
     * @Route("/admin/AfficheUser/{id}",name="Detailm")
     */
    function affichemd(UserRepository $repo,$id){

        $user=$repo->find($id);

        return $this->render("user/AfficheMedPha.html.twig",
            ['user'=>$user]);
    }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/admin/AffichPharmacien",name="AffichePharmacien")
     */
    function affichepha(UserRepository $repo){

        $user=$repo->findAll();

        return $this->render("user/AffichePharamacien.html.twig",
            ['user'=>$user]);
    }
    /**
     * @Route("/admin/AffichPharmacienajax",name="AffichePharmacienajax")
     */
    function affichePharmacienForajax(UserRepository $repository,Request $request){
        $requestString=$request->get('searchValue');
        $user = $repository->findUserByname($requestString);
        return $this->render('user/PharmacienAjaxBack.html.twig' ,[
            "user"=>$user,
        ]);
    }





    /**
     * @param $id
     * @param UserRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *@Route("admin/Deleteu/{id}",name="Delete")
     */
    function Delete($id, UserRepository $repo){
        $user=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("user");
    }

    /**
     * @param ReclamationRepository $repRec
     * @param UserRepository $repUser
     * @param $id
     * @return Response
     * @Route("/user/listrec/{id}", name="usereclamation")
     */

    function ListReclamationBuUser(ReclamationRepository $repRec, UserRepository $repUser,$id){
        $user=$repUser->find($id);
        $reclamation=$repRec->listReclamationByUser($user->getId());
        return $this->render("user/show.html.twig",['rec'=>$reclamation]);


    }

    /**
     * @param UserRepository $rep
     * @param $id
     * @param UserRepository $rep1
     * @return Response
     *@Route("/user/affiche/{id}", name="userrrrr")
     */
    function AfficheUserid(UserRepository $rep,$id,UserRepository $rep1){
        $user=$rep->find($id);
        $userr=$rep1->AfficheUserqb($user->getId());
        return $this->render("registration/UserInterface.html.twig", ['user' => $user]);
    }

    /**
     * Modifier un utulisateur
     *
     * @Route("/user/modifier/{id}",name="modifierutilisateur")
     * @param Request $request
     * @param UserRepository $repo
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editUser( Request $request, UserRepository $repo,$id){
        $user=$repo->find($id);
        $form = $this->createForm(EditUserType::class,$user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('user');
        }

        return $this->render('user/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param UserRepository $repository
     * @param $id
     * @param \Swift_Mailer $mailer
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/contact/{id}",name="contact")
     */
        public function contact(Request $request, \Swift_Mailer $mailer,$id,ReclamationRepository $repository){
            $reclamation=$repository->find($id);
            $form = $this->createForm(ContactType::class);
            $form->add("Envoyer votre reponse ",SubmitType::class);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $contactFormData = $form->getData();
                dump($contactFormData);
                $message = (new \Swift_Message('Réclamation!'))
                                   ->setFrom('docdocpidev@gmail.com')
                              ->setTo($reclamation->getUser()->getEmail())
                               ->setBody(
                                      $contactFormData['message'],
                                       'text/plain'
                                  )
                           ;

           $mailer->send($message);
           $em=$this->getDoctrine()->getManager();
           $reclamation->setEtat( true);
           $em->flush();
           $this->addFlash('success','Votre message a été envoyé');

                      }
            return $this->render('user/contact.html.twig',['form'=>$form->createView(),'reclamation'=>$reclamation]);
        }

    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("/user/AfficheMedecin",name="UserAfficheMedecin")
     */
        function affichemedForUser(UserRepository $repo,PaginatorInterface $paginator,Request $request){

        $donnes=$repo->findAll();
        $user = $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),4
        );

        return $this->render("user/AfficheMedecinForUser.html.twig",
            ['user'=>$user]);
    }
    /**
     * @Route("/admin/AfficheMedecinajax",name="UserAfficheMedecinajax")
     */
/*    function affichemedForUserajax(UserRepository $repository,Request $request){
        $requestString=$request->get('searchValue');
        $user = $repository->findUserByname($requestString);
        return $this->render('user/AfficheMedecinajax.html.twig' ,[
            "user"=>$user,
        ]);
    }*/
    /**
     * @param UserRepository $repo
     * @return Response
     * @Route("AfficheMedecinFront",name="UserAfficheMedecinFront")
     */
    function affichemedForUserFront(UserRepository $repo,PaginatorInterface $paginator,Request $request){

        $donnes=$repo->findAll();
        $user = $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),4
        );

        return $this->render("user/AfficheMedecinForUser.html.twig",
            ['user'=>$user]);
    }

    /**
     * @param UserRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Route("/user/AffichePharmacien",name="UserAffichePharamacien")
     */
    function affichephaForUser(UserRepository $repo,PaginatorInterface $paginator,Request $request){

        $donnes=$repo->findAll();
        $user = $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),4
        );

        return $this->render("user/AffichePharamcienForUser.html.twig",
            ['user'=>$user]);
    }
    /**
     * @param UserRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     * @Route("AffichePharmacienFront",name="UserAffichePharamacienFront")
     */
    function affichephaForUserFront(UserRepository $repo,PaginatorInterface $paginator,Request $request){

        $donnes=$repo->findAll();
        $user = $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),4
        );

        return $this->render("user/AffichePharamcienForUser.html.twig",
            ['user'=>$user]);
    }

/**
     * @param UserRepository $rep
     * @param $id
     * @param UserRepository $rep1
     * @return Response
     *@Route("/loginmobile/{email}/{password}", name="logiiin")
     */
    function AfficheLogin(UserRepository $rep,$email,$password,NormalizerInterface $normalizer){
        $user=$rep->findOneBy(array('email'=>$email));
        if($user){
            $jsonContent = $normalizer->normalize($user, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($jsonContent));
        }else{

            return new Response(json_encode('false'));
        }
    }

    /**
     * @Route("/user/rechercheNomPaiementMobile/{nom}",name="rechercheParNomMobile")
     * @param $nom
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function RechercheeMobile($nom, NormalizerInterface $normalizer)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users=$repo->findBy(array('nom' => $nom));
        $json= $normalizer->normalize($users, 'json',['groups' => 'post:read']);
        return new Response(json_encode($json));
    }

}

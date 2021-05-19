<?php

namespace App\Controller;

use App\Entity\Consultation;
use App\Entity\Question;
use App\Entity\User;
use App\Form\ConsultationType;
use App\Repository\ConsultationRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class ConsultationController extends AbstractController
{
    /**
     * @Route("/Liste-des-consultations", name="consultation")
     * @param ConsultationRepository $repo
     * @return Response
     */
    public function Affiche(ConsultationRepository $repo)
    {
        $consultation = $repo->findAll();
        return $this->render('consultation/AfficherConsultation.html.twig', ['consultation' => $consultation]);

    }

    /**
     * @Route ("/suppConsultation/{id}",name="suppConsultation")
     * @param ConsultationRepository $repo
     * @param $id
     * @return RedirectResponse
     */
    public function supprimer($id, ConsultationRepository $repo)
    {
        $consultationRepository = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($consultationRepository);
        $em->flush();
        return $this->redirectToRoute('consultation');
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/ajouter-une-Consultation/{userid}/{idMed}",name="AddConsultation")
     */

    public function Ajouter(Request $request, $userid , UserRepository $repo , $idMed , UserRepository $userRepo)
    {
        $user=$userRepo->find($idMed);
        $consultation = new Consultation();
        $consultation->setUserM($user);
        $user=$repo->find($userid);
        $consultation->setUser($user);
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request); //parcourir la requete et extraire les champs du form et l'entité
        if ($form->isSubmitted() && $form->isValid()) {
            $consultation->setIsAccepted(null);
            $em = $this->getDoctrine()->getManager();
            $em->persist($consultation);
            $em->flush();
            if ($user->getType()=="medecin")
            {return $this->redirect('/Liste-des-consultations-medecin/'.$userid);}

            else
            {return $this->redirect('/Liste-des-consultations-patient/'.$userid);}
        }
        //la vue qui va gérer la vue de l'ajout
        // du formulaire pas dans la condition si vous avez remarqué
        return $this->render('consultation/AjouterConsultation.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @param ConsultationRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/modifier-une-consultation/{id}/{idUser}",name="UpdateConsultation")
     */
    public function modifier(ConsultationRepository $repo, $id, Request $request , $idUser)
    {
        $consultation = $repo->find($id);
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->add('Update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect('/Liste-des-consultations-patient/'.$idUser);
        }
        return $this->render('consultation/UpdateConsultation.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param ConsultationRepository $repo
     * @return Response
     * @Route ("/Liste-des-consultations-medecin/{id}", name="consultationMedecin")
     */
    public function afficherConsultationMedecin(ConsultationRepository $repo, $id )
    {
        $consultation = $this->getDoctrine()
            ->getRepository(Consultation::class)
            ->findForDoctor($id);

        return $this->render('consultation/AfficherConsultationMedecin.html.twig', ['consultation' => $consultation]);


    }
    /**
     * @param ConsultationRepository $repo
     * @return Response
     * @Route ("/Liste-des-consultations-patient/{id}", name="consultationPatient")
     */
    public function afficherConsultationPatient(ConsultationRepository $repo, $id , Request $request, PaginatorInterface  $paginator)
    {
        $consultation = $this->getDoctrine()
            ->getRepository(Consultation::class)
            ->findForPatient($id);
        // $donnees=$this->getDoctrine()->getRepository(Consultation::class)->findBy([],['datehr'=>'desc']);
        $consultations = $paginator->paginate(
            $consultation,
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('consultation/AfficherConsultationPatient.html.twig', ['consultation' => $consultation, 'consultations'=>$consultations]);

    }

    /**
     * @param ConsultationRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/accepter-Consultation/{id}",name="AccepterConsultation")
     */
    public function AccepterConsultation(ConsultationRepository $repo, $id, Request $request)
    {
        $consultation = $repo->find($id);
        $consultation->setIsAccepted(true);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

        return $this->redirect('/Liste-des-consultations-medecin/'.$id);
    }
    /**
     * @Route("/afficher-consultation-medecin",name="consultationMed")
     * @Method("GET")
     */
    public function afficherConsultationMed(Request $request,NormalizerInterface $normalizer)
    {
        // /afficher-consultation-medecin?idUser=6
        $id=$request->get("idUser");
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($id);
        $encoder= new JsonEncoder();
        $json=$normalizer->normalize($user->getConsultationsM(),'json',['groups'=>['consultation']]);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/afficher-consultation-patient",name="consultationPatient")
     * @Method("GET")
     */
    public function afficherConsultationPatientJ(Request $request,NormalizerInterface $normalizer)
    {
        // /afficher-consultation-patient?idUser=6
        $id=$request->get("idUser");
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($id);
        $encoder= new JsonEncoder();
        $json=$normalizer->normalize($user->getConsultations(),'json',['groups'=>['consultation']]);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/supp-consultation-json",name="suppConsultation")
     * @Method("DELETE")
     */
    public function supprimerConsultationJ(Request $request,     NormalizerInterface $serializer)
    {
        // /supp-consultation-json?id=123
        $id= $request->get("id");
        $em=$this->getDoctrine()->getManager();
        $consultation=$em->getRepository(Consultation::class)->find($id);
        if($consultation!=null)
        {
            $em->remove($consultation);
            $em->flush();
            $json=$serializer->normalize("consultation supprimee!");
            return new Response(json_encode($json));
        }
    }
    /**
     * @Route("/edit-consultation-json",name="EditConsultationJson")
     * @Method("PUT")
     */

    public function modifierConsultationJ(Request $request,NormalizerInterface $serializer)
    {
        // /edit-consultation-json?id=10&date=2019-02-04&hr=00:00:00
        $em=$this->getDoctrine()->getManager();
        $consultation=$this->getDoctrine()->getManager()->getRepository(Consultation::class)->find($request->get("id"));
        $date=$request->query->get("date");
        $heure=$request->query->get("hr");
        $datehrS=$date." ".$heure;
        $date = new \DateTime($datehrS);
        $consultation->setDatehr($date);
        $em->persist($consultation);
        $em->flush();
        $json=$serializer->normalize($consultation,'json',['groups'=>['consultation']]);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/add-consultation-json",name="AddConsJson")
     * @Method("POST")
     */

    public function AjouterConsultationJ(Request $request,NormalizerInterface $serializer, UserRepository $repo1 )
    {
        // /add-consultation-json?date=2019-02-04&hr=00:00:00&medid=6&pid=1
        $consultation=new Consultation();
        $em=$this->getDoctrine()->getManager();
        $patientId=$request->query->get("medid");
        $medecinId=$request->query->get("pid");
        $consultation->setUser($repo1->find($patientId));
        $consultation->setUserM($repo1->find($medecinId));
        $consultation->setIsAccepted(0);
        $date=$request->query->get("date");
        $heure=$request->query->get("hr");
        $datehrS=$date." ".$heure;
        $date = new \DateTime($datehrS);
        $consultation->setDatehr($date);
        $em->persist($consultation);
        $em->flush();
        $json=$serializer->normalize($consultation,'json',['groups'=>['consultation']]);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/accepter-consultation-json",name="EditConsJson")
     * @Method("PUT")
     */

    public function AccepterConsultationJ(Request $request,NormalizerInterface $serializer)
    {
        // /accepter-consultation-json?id=10
        $em=$this->getDoctrine()->getManager();
        $consultation=$this->getDoctrine()->getManager()->getRepository(Consultation::class)->find($request->get("id"));
        $consultation->setIsAccepted(1);
        $em->persist($consultation);
        $em->flush();
        $json=$serializer->normalize($consultation,'json',['groups'=>['consultation']]);
        return new Response(json_encode($json));
    }





}


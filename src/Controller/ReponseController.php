<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ReponseController extends AbstractController
{
    /**
     * @param ReponseRepository $repo
     * @return Response
     * @Route("/liste-des-reponses",name="AffReponse")
     */
    public function Affiche(ReponseRepository $repo)
    {
        $reponse=$repo->findAll();
        return $this->render('reponse/afficherReponse.html.twig',['reponse' => $reponse]);
    }

    /**
     * @Route ("/suppReponse/{id}/{idUser}",name="suppReponse")
     * @param $id
     * @param ReponseRepository $repo
     * @return RedirectResponse
     */

    public function supprimer($id,ReponseRepository $repo, $idUser)
    {
        $reponse=$repo->find($id);
        $idQ=$reponse->getQuestion()->getId();
        $em=$this->getDoctrine()->getManager();
        $em->remove($reponse);
        $em->flush();
        return $this->redirect('/afficher-une-question/'.$idQ.'/'.$idUser);
    }




    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/ajouter-une-Reponse",name="ajouterR")
     */
    public function Ajouter(Request $request)
    {
       $reponse= new Reponse();
        $form=$this->createForm(ReponseType::class,$reponse);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){
            $em=$this->getDoctrine()->getManager();
            $em->persist($reponse);
            $em->flush();
            return $this->redirectToRoute('AffReponse');
        }
        return $this->render('reponse/AjouterReponse.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @param ReponseRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/modifier-reponse/{id}/{idUser}",name="updateReponse")
     *
     */
    public function  modifier(ReponseRepository $repo,$id,Request $request,$idUser)
    {
        $reponse = $repo->find($id);
        $question=$reponse->getQuestion();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->add('Modifier',SubmitType::class, ['attr'=>['class'=>'btn btn-info pull-right']]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirect('/afficher-une-question/'.$question->getId().'/'.$idUser);

        }
        return $this->render('reponse/UpdateReponse.html.twig', ['q' => $question,'form'=>$form->createView()]);
    }
    /**
     * @Route("/afficher-reponses-par-question-json",name="uneQJson")
     * @Method("GET")
     */
    public function afficherReponsesJ(Request $request,NormalizerInterface $normalizer)
    {
        // /afficher-reponses-par-question-json?id=19
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $question=$em->getRepository(Question::class)->find($id);
        $encoder= new JsonEncoder();
        $json=$normalizer->normalize($question->getReponses(),'json',['groups'=>['reponse']]);
        return new Response(json_encode($json));
    }
    /**
     * @Route("/delete-reponse-json",name="DeleteRJson")
     * @Method("DELETE")
     */
    public function supprimerReponseJ(Request $request, NormalizerInterface $serializer)
    {
        // /delete-reponse-json?id=123
        $id= $request->get("id");
        $em=$this->getDoctrine()->getManager();
        $reponse=$em->getRepository(Reponse::class)->find($id);
        if($reponse!=null)
        {
            $em->remove($reponse);
            $em->flush();
            $json=$serializer->normalize("Reponse supprimee!");
            return new Response(json_encode($json));
        }
    }
    /**
     * @Route("/edit-reponse-json",name="EditQJson")
     * @Method("PUT")
     */

    public function modifierReponseJ(Request $request,NormalizerInterface $serializer)
    {
        // /edit-reponse-json?id=44&description="ghjhgfghjkjhghjkjhgh"
        $em=$this->getDoctrine()->getManager();
        $reponse=$this->getDoctrine()->getManager()->getRepository(Reponse::class)->find($request->get("id"));
        $description=$request->query->get("description");
        $reponse->setDescription($description);
        $em->persist($reponse);
        $em->flush();
        $json=$serializer->normalize($reponse,'json',['groups'=>['reponse']]);
        return new Response(json_encode($json));
    }

    /**
     * @Route("/add-reponse-json",name="AddRJson")
     * @Method("POST")
     */

         public function AjouterReponseJ(Request $request,QuestionRepository $repoQ, UserRepository $repoU,NormalizerInterface $serializer)
    {

        // /add-reponse-json?description="ghjhgfghjkjhghjkjhgh"&userid=1&questionid=2
         $reponse=new Reponse();
        $dictionnaire=['stop','why','what','way'];
        $reponse->setIsBad(false);
        $em=$this->getDoctrine()->getManager();
        $description=$request->query->get("description");
        $userid=$request->query->get("userid");
        $questionid=$request->query->get("questionid");
        $reponse->setDescription($description);
        $reponse->setQuestion($repoQ->find($questionid));
        $reponse->setUser($repoU->find($userid));
        $test=explode(" ",$description);
        foreach($dictionnaire as $d)
        {
            foreach ($test as $t)
            {
                if ($t==$d)
                {
                    $reponse->setIsBad(true);
                }
            }
        }
        $em->persist($reponse);
        $em->flush();
        $json=$serializer->normalize($reponse,'json',['groups'=>['reponse']]);
        return new Response(json_encode($json));
    }




}

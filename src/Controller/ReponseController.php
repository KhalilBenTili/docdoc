<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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


}

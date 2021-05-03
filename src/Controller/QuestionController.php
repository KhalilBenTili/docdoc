<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Repository\ReponseRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;



class QuestionController extends AbstractController
{
    /**
     * @param QuestionRepository $repo
     * @return Response
     * @Route ("/Liste-des-questions", name="AffQuestion")
     */
    public function Affiche(QuestionRepository $repo)
    {
        $q = $repo->findAll();
        //hello
        return $this->render('question/AfficherQuestion.html.twig', ['q' => $q]);
    }
    /**
     * @param QuestionRepository $repo
     * @return Response
     * @Route ("/admin/Liste-des-questions", name="AffQuestionAdmin")
     */
    public function AfficheAdmin(QuestionRepository $repo)
    {
        $q = $repo->findAll();
        //hello
        return $this->render('question/AfficherQuestionAdmin.html.twig', ['q' => $q]);
    }

    /**
     * @param $id
     * @param QuestionRepository $repo
     * @return RedirectResponse
     * @Route ("/suppQ/{id}",name="suppQ")
     */
    public function supprimer($id, QuestionRepository $repo)
    {
        $question = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('AffQuestion');

    }
    /**
     * @param $id
     * @param QuestionRepository $repo
     * @return RedirectResponse
     * @Route ("/admin/suppQ/{id}",name="suppQAdmin")
     */
    public function supprimerAdmin($id, QuestionRepository $repo)
    {
        $question = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($question);
        $em->flush();
        return $this->redirectToRoute('AffQuestionAdmin');

    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("/poser-une-Question/{userid}",name="ajouterQuestion")
     */
    function ajouter(Request $request, $userid , UserRepository $repoUser1)
    {
        $question = new Question();
        $question->setIsAnswered(0);
        $user1=$repoUser1->find($userid);
        $question->setUser($user1);
        $form = $this->createForm(QuestionType::class, $question);
        $form->add('Ajouter', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();
            return $this->redirectToRoute('AffQuestion');
        }

        return $this->render('question/AjouterQuestion.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param QuestionRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse|Response
     * @Route ("modifier-une-question/{id}",name="modifierQ")
     */
    function modifier(QuestionRepository $repo, $id, Request $request)

    {
        $question = $repo->find($id);
        $form = $this->createForm(QuestionType::class, $question);
        $form->add('Update', SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AffQuestion');
        }
        return $this->render('question/UpdateQuestion.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @param QuestionRepository $repo
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/afficher-une-question/{id}/{userid}",name="AfficherQ")
     */
    function AfficherUneQuestion(QuestionRepository $repo,UserRepository $repoU,$userid, $id, Request $request , UserRepository $urepo)
    {

        $dictionnaire=['stop','why','what','way'];
        $question=$repo->find($id);
        $reponse= new Reponse();
        $reponse->setQuestion($question);
        $form=$this->createForm(ReponseType::class,$reponse);
        $userR=$repoU->find($userid);
        $reponse->setUser($userR);

        $description="";
        $reponse->setIsBad(false);
        $nbIsBad=null;
        $test=false;

        /* debut is blocked*/
        /*tester si l'user possede des antecedents dans les mots inappropriés*/

        $query = $this->getDoctrine()
            ->getRepository(Reponse::class)
            ->CountIsBad($userid);

        $nbIsBad=sizeof($query);
        /*fin test*/

        if($nbIsBad>=3)
        {
            $user=$repoU->find($userid);
            $user->setIsBlocked(true);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            $test=true;


        } else

            {$form->add('Commenter',SubmitType::class, ['attr'=>['class'=>'btn btn-info pull-right']]);
                $form->handleRequest($request);
            }

        /*fin isBlocked*/




        if ($form->isSubmitted() && $form->isValid() ){

            $description=$reponse->getDescription();
            $test=explode(" ",$description);
            foreach($dictionnaire as $d)
            {
                foreach ($test as $t)
                {
                    if ($t==$d)
                    {
                        $reponse->setIsBad(true);

                        $em=$this->getDoctrine()->getManager();
                        $em->persist($reponse);
                        $em->flush();

                    }
                }
            }
            if($reponse->getIsBad()==false)
            {
                $em=$this->getDoctrine()->getManager();
                $em->persist($reponse);
                $em->flush();

            }

        }

        return $this->render('question/AfficherUneQuestion.html.twig', ['q' => $question,'isBad' => $reponse->getIsBad(),'test'=>$test,'nbIsBad'=>$nbIsBad,'description' => $description,'form'=>$form->createView()]);

    }


}

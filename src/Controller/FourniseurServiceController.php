<?php

namespace App\Controller;

use App\Entity\FourniseurService;
use App\Entity\Service;
use App\Form\FournisseurType;
use App\Repository\FourniseurServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FourniseurServiceController extends AbstractController
{
    /**
     * @Route("/admin/fourniseur/service", name="fourniseur_service")
     */
    public function index(): Response
    {
        return $this->render('fourniseur_service/index.html.twig', [
            'controller_name' => 'FourniseurServiceController',
        ]);
    }
    /**
     * @Route("/admin/fourniseur/service/affiche",name="afficherfourniseur")
     */
    public function affiche(){
        $repo=$this->getDoctrine()->getRepository(FourniseurService::class)->findAll();
        $repos=$this->getDoctrine()->getRepository(Service::class)->findAll();
        return $this->render('fourniseur_service/affiche.html.twig',['fourniseur'=>$repo,'services'=>$repos]);
    }
    /**
     * @Route("/admin/fourniseur/service/details/{id}",name="detailsfourniseur")
     */
    /**
     * @param Request $request
     * @return Response
     * @Route ("/admin/searchFourniseur",name="searchFourniseur")
     */
    public function searchFourniseur(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(FourniseurService::class);
        $searchfield=$request->get('searchValue');
        $fourniseur = $repository->searchFournniseur($searchfield);
        return $this->render('fourniseur_service/search.html.twig' ,[
            "fourniseur"=>$fourniseur
        ]);
    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("/admin/searchFourniseurserv",name="searchFourniseurserv")
     */
    public function searchFourniseurServ(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(FourniseurService::class);
        $repos=$this->getDoctrine()->getRepository(Service::class)->findAll();

        $searchfield=$request->get('searchValue');
        $fourniseur = $repository->fournisseurParServiceLibelle($searchfield);
        return $this->render('fourniseur_service/search.html.twig' ,[
            "fourniseur"=>$fourniseur,'services'=>$repos
        ]);
    }
    /**
     * @Route("/admin/fourniseur/service/delete/{id}",name="deletefourniseur")
     */
    public function delete($id,FourniseurServiceRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $fourniseur=$repo->find($id);
        $em->remove($fourniseur);
        $em->flush();
        return $this->redirectToRoute('afficherfourniseur');
    }
    /**
     * @Route("/admin/fourniseur/service/ajouter",name="Ajouterfourniseur")
     */
    function Ajout(Request $request){
        $fourniseur=new FourniseurService();
        $form=$this->createForm(FournisseurType::class,$fourniseur);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($fourniseur);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherfourniseur");
        }
        return $this->render('fourniseur_service/ajout.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/admin/fourniseur/service/update/{id}",name="updatefourniseur")
     */

    function update($id,FourniseurServiceRepository $repo,Request $request){
        $fourniseur=$repo->find($id) ;
        $form=$this->createForm(FournisseurType::class, $fourniseur);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherfourniseur");
        }

        return $this->render("fourniseur_service/update.html.twig",['f'=>$form->createView()]);

    }
}

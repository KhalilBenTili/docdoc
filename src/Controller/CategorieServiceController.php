<?php

namespace App\Controller;

use App\Entity\CategorieService;
use App\Form\CategorieServiceType;
use App\Repository\CategorieServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieServiceController extends Controller
{
    /**
     * @Route("/admin/categorie/service", name="categorie_service")
     */
    public function index(): Response
    {
        return $this->render('categorie_service/index.html.twig', [
            'controller_name' => 'CategorieServiceController',
        ]);
    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("/searchCategorie",name="searchCategorie")
     */
    public function searchCategorie(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(CategorieService::class);
        $searchfield=$request->get('searchValue');
        $categories = $repository->searchCategorie($searchfield);
        return $this->render('categorie_service/categories.html.twig' ,[
            "categories"=>$categories
        ]);
    }
    /**
     * @Route("/admincategorie/service/affiche",name="affichercatservice")
     */
    public function affiche(Request $request){
        $categories=$this->getDoctrine()->getRepository(CategorieService::class)->findAll();

     /*  /* $paginator=$this->get('knp_paginator');
        $cats =$paginator->paginate(
            $categories,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',3)*/

        return $this->render('categorie_service/affiche.html.twig',['categories'=>$categories]);
    }
    /**
     * @Route("/admin/service/categorie/details/{id}",name="detailscatservice")
     */
    public function affichedetails($id){
        $repo=$this->getDoctrine()->getRepository(CategorieService::class)->find($id);
        return $this->render('categorie_service/details.html.twig',['categorie'=>$repo]);
    }
    /**
     * @Route("/admin/service/categorie/delete/{id}",name="deletecatservice")
     */
    public function delete($id,CategorieServiceRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $categorie=$repo->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('affichercatservice');
    }
    /**
     * @Route("/admin/service/categorie/ajouter",name="Ajoutercatservice")
     */
    function Ajout(Request $request){
        $categorie=new CategorieService();
        $form=$this->createForm(CategorieServiceType::class,$categorie);

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorie);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("affichercatservice");
        }
        return $this->render('categorie_service/ajout.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("/admin/service/categorie/update/{id}",name="updatecatservice")
     */



    function update($id,CategorieServiceRepository $repo,Request $request){
        $categorie=$repo->find($id) ;
        $form=$this->createForm(CategorieServiceType::class,$categorie);

        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();//maj de la BD
            return $this->redirectToRoute("affichercatservice");
        }

        return $this->render("categorie_service/update.html.twig",['f'=>$form->createView()]);

    }

}

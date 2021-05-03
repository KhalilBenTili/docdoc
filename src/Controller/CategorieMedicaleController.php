<?php

namespace App\Controller;

use App\Entity\CategorieMedicale;
use App\Form\CategorieMedicaleType;
use App\Repository\CategorieMedicaleRepository;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieMedicaleController extends AbstractController
{
    /**
     * @param CategorieMedicaleRepository $repo
     * @return Response
     * @Route ("/admin/liste-des-categories-medicales",name="afficherCategorieMedicale")

     */
    public function Affichem(CategorieMedicaleRepository $repo){
        // $repo=$this->getDoctrine()->getRepository(CategorieMedicale::class);
        $categorieMedicale1=$repo->findAll();
        return $this->render('categorie_medicale/categorieMedicale.html.twig',['categorieMedicale' => $categorieMedicale1]);

    }
    /**
     * @Route ("/admin/suppCatMedicale/{id}",name="suppCatMedicale")
     * @param CategorieMedicaleRepository $repo
     * @param $id
     * @return RedirectResponse
     */
    public function supprimer($id,CategorieMedicaleRepository $repo){
        $categorieMed=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($categorieMed);
        $em->flush();
        return $this->redirectToRoute('AddCatMed');
    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("/admin/ajouter-une-categorie-medicale",name="AddCatMed")
     */

    public function Ajouter(Request $request, CategorieMedicaleRepository $repo){
        $categorieMedicale= new CategorieMedicale();
        $categorieMedicale1=$repo->findAll();
        $form=$this->createForm(CategorieMedicaleType::class,$categorieMedicale);
        $form->add('Ajouter',SubmitType::class, ['attr'=>['class'=>'btn btn-info pull-right']]); //bouton dans le controller pour des raisons de sécurité
        $form->handleRequest($request); //parcourir la requete et extraire les champs du form et l'entité
        if ($form->isSubmitted() && $form->isValid() ){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorieMedicale);
            $em->flush();
            return $this->redirectToRoute('AddCatMed');

        }
        //la vue qui va gérer la vue de l'ajout
        // du formulaire pas dans la condition si vous avez remarqué
        return $this->render('categorie_medicale/AddCategorieMedicale.html.twig', ['form'=>$form->createView(), 'categorieMedicale' => $categorieMedicale1]);

    }
    /**
     * @Route("/admin/modifier-une-categorie-medicale/{id}",name="updateCatMedicale")
     * @param CategorieMedicaleRepository $repo
     * @param $id
     * @param Request $request
     * @return RedirectResponse
     */
    public function modifier(CategorieMedicaleRepository $repo,$id, Request $request, CategorieMedicaleRepository $repo1){
        $categorieMedicale=$repo->find($id);
        $categorieMedicale1=$repo1->findAll();
        $form=$this->createForm(CategorieMedicaleType::class,$categorieMedicale);
        $form->add('Update',SubmitType::class,['attr'=>['class'=>'btn btn-info pull-right']]); //bouton dans le controller pour des raisons de sécurité
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AddCatMed');
        }

        return $this->render('categorie_medicale/AddCategorieMedicale.html.twig', ['form'=>$form->createView(), 'categorieMedicale' => $categorieMedicale1]);
    }

    /**
     * @param Request $request
     * @param PaginationInterface $paginator
     * @return Response

     */
  /*  public function Affiche(Request $request , PaginationInterface $paginator){
       $donnee=$this->getDoctrine()->getRepository(CategorieMedicale::class)->findBy([],['nom'=> 'desc']);
       $categorieMedicale=$paginator->paginate(
           $donnee,
           $request->query->getInt('page',1),
           5
       );
        return $this->render('categorie_medicale/categorieMedicale.html.twig',['categorieMedicale' => $categorieMedicale1]);

    }*/











}

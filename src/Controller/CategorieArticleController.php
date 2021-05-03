<?php

namespace App\Controller;

use App\Entity\CategorieArticle;
use App\Form\CategorieArticleType;
use App\Repository\CategorieArticleRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieArticleController extends AbstractController
{
    /**
     * @Route("/admin/categorie/article", name="categorie_article")
     */
    public function index(): Response
    {
        return $this->render('categorie_article/index.html.twig', [
            'controller_name' => 'CategorieArticleController',
        ]);
    }
    /**
     * @Route("/admin/categorie/affiche",name="affichercatcategorie")
     */
    public function affiche(){
        $repo=$this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();
        return $this->render('categorieArticle/affiche.html.twig',['categorie'=>$repo]);
    }
    /**
     * @Route("/admin/categorie/details/{id}",name="detailscatcategorie")
     */
    public function affichedetails($id){
        $repo=$this->getDoctrine()->getRepository(CategorieArticle::class)->find($id);
        return $this->render('categorieArticle/details.html.twig',['categorie'=>$repo]);
    }
    /**
     * @Route("admin/categorie/delete/{id}",name="deletecatcategorie")
     */

    public function delete($id,CategorieArticleRepository $repo , FlashyNotifier $flashy){
        $em=$this->getDoctrine()->getManager();
        $categorie=$repo->find($id);
        $em->remove($categorie);
        $em->flush();
        $flashy->success('catégorie effacée');
        return $this->redirectToRoute('affichercatcategorie');
    }


    /**
     * @Route("/admin/categorie/ajouter",name="Ajoutercatcategorie")
     */
    function Ajout(Request $request,FlashyNotifier $flashy){
        $categorie=new CategorieArticle();
        $form=$this->createForm(CategorieArticleType::class,$categorie);

        $form->handleRequest($request);

        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorie);//insert into
            $em->flush();//maj de la BD
            $flashy->success('catégorie ajoutée');
            return $this->redirectToRoute("affichercatcategorie");
        }
        return $this->render('categorieArticle/ajout.html.twig',['form'=>$form->createView()]);
    }

    /**
     * @Route("admin/categorie/update/{id}",name="updatecatcategorie")
     * @param $id
     * @param CategorieArticleRepository $repo
     * @param Request $request
     * @param $categorie
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */

    function update($id, CategorieArticleRepository $repo, Request $request,FlashyNotifier $flashy){
        $categorie=$repo->find($id) ;
        $form = $this->createForm(CategorieArticleType::class,$categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('categorie mise à jour');

            return $this->redirectToRoute('affichercatcategorie');
        }

        return $this->render('categorieArticle/update.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }


}

<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Produit;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    /**
     * @Route("/afficherC",name="afficheC")
     */
    public function afficheC(){
        $repo=$this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('categorie/afficheC.html.twig',['repo'=>$repo]);
    }

    /**
     * @Route("/deleteC/{id}",name="deleteC")
     */
    public function deleteC($id,CategorieRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $categorie=$repo->find($id);
        $em->remove($categorie);
        $em->flush();
        return $this->redirectToRoute('afficheC');
    }
    /**
     * @Route("/ajouterC",name="AjouterC")
     */
    function AjoutC(Request $request){

        $categorie=new Categorie();
        //je fais appel a un formulaire déja crée methode 1
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->add("Ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            return $this->redirectToRoute("afficheC");
        }
        return $this->render("categorie/ajoutC.html.twig",['form'=>$form->createView()]);
    }
    /**
     * @Route("/updateC/{id}",name="updateC")
     */
    function updateC($id,CategorieRepository $repo,Request $request){
        $categorie=$repo->find($id);
        //je fais appel a un formulaire déja crée methode 1
        $form=$this->createForm(CategorieType::class,$categorie);
        $form->add("update",SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("afficheC");
        }

        return $this->render("categorie/updateC.html.twig",['form'=>$form->createView()]);

    }

    /**
     * @Route("/searchCategoriex ", name="searchCategoriex")
     */
    public function searchCategoriex(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Categorie::class);
        $requestString=$request->get('searchValue');
        $categories = $repository->findCategorieByNom($requestString);
        $jsonContent = $Normalizer->normalize($categories, 'json',['groups'=>'categories']);
        $retour=json_encode($jsonContent);
        return new Response($retour);
    }


    /**
     * @param NormalizerInterface $normalizer
     * @param CategorieRepository $repo
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     * @Route ("/afficherCMobile",name="afficherCMobile")
     */
    public function afficheMobile(NormalizerInterface $normalizer, CategorieRepository $repo){
        $categories=$repo->findAll();
        $jsonContent = $normalizer->normalize($categories,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/ajouterCMobile",name="ajouterCMobile")
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    function ajouterCMobile(Request $request,NormalizerInterface $normalizer){

        $em = $this->getDoctrine()->getManager();
        $categorie = new Categorie();
        $categorie->setTitre($request->get('titre'));
        $categorie->setDescription(($request->get('description')));

        $em->persist($categorie);
        $em->flush();
        $jsonContent = $normalizer->normalize($categorie, 'json', ['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }
    /**
     * @Route("/modifierCMobile",name="modifierCMobile")
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    function modifierCMobile(Request $request,NormalizerInterface $normalizer,$id){

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);
        $categorie->setTitre($request->get('titre'));
        $categorie->setDescription(($request->get('description')));
        $em->flush();
        $jsonContent = $normalizer->normalize($categorie, 'json', ['groups'=>'post:read']);
        return new Response("Modification reussie".json_encode($jsonContent));


    }

    /**
     * @Route("/supprimerCMobile/{id}",name="supprimerCMobile")
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    function supprimerCMobile(NormalizerInterface $normalizer,$id){

        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);
        $em->remove($categorie);
        $em->flush();
        $jsonContent = $normalizer->normalize($categorie, 'json', ['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));


    }

}

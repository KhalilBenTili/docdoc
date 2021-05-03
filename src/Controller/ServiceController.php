<?php

namespace App\Controller;

use App\Entity\CategorieService;
use App\Entity\Rate;
use App\Entity\Service;
use App\Form\RateType;
use App\Form\ServiceType;
use App\Repository\CategorieServiceRepository;
use App\Repository\RateRepository;
use App\Repository\ServiceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends AbstractController
{

    /**
     * @Route("service/affiche/{categorie}", name="afficheservicesparcategorie")
     */
    public function caffiche(ServiceRepository $repos,CategorieServiceRepository $repoc,$categorie){

        $servicecat=$repos->findallbycategorie($categorie);
        $cat=$repoc->find($categorie);
        return $this->render('service/Caffiche.html.twig',['services'=>$servicecat,'categorie'=>$cat]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route ("/searchService",name="searchService")
     */
    public function searchService(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Service::class);
        $categories=$this->getDoctrine()->getRepository(CategorieService::class)->findAll();

        $searchfield=$request->get('searchValue');
        $services = $repository->searchService($searchfield);
        return $this->render('service/searchA.html.twig' ,[
            'services'=>$services
        ]);
    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("/searchservicecat",name="searchServicecat")
     */
    public function searchServicecat(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Service::class);
        $searchfield=$request->get('searchValue');
        $services = $repository->searchServicecat($searchfield);
        return $this->render('service/searchA.html.twig' ,[
            'services'=>$services
        ]);
    }
    /**
     * @Route("/services", name="services")
     */
    public function services(ServiceRepository $repo, Request $request)
    {
        $services = $repo->findAll();
        return $this->render('service/services.html.twig',['services'=>$services]);

    }
    /**
     * @param Request $request
     * @return Response
     * @Route ("/searchServices",name="searchServices")
     */
    public function searchServices(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Service::class);
        $searchfield=$request->get('searchValue');
        $services = $repository->searchService($searchfield);
        return $this->render('service/filter.html.twig' ,[
            'services'=>$services
        ]);
    }
    /**
     * @Route("/triservice", name="triservice")
     */
    public function triservices(ServiceRepository $repo, Request $request){
        $trifield=$request->get('triValue');

        if($trifield=2){
            $services=$repo->orderbyprix();
        }
        elseif ($trifield=1){
            $services=$repo->orderbylibelle();
        }
        else{
            $services=$repo->findAll();
        }
        return $this->render('service/filter.html.twig',['services'=>$services]);
    }
    /**
     * @Route("admin/service/delete/{id}",name="deleteservice")
     */
    public function delete( $id ,ServiceRepository $repo){
        $em=$this->getDoctrine()->getManager();
        $service=$repo->find($id);
        $em->remove($service);
        $em->flush();
        return $this->redirectToRoute('afficherservice');
    }
    /**
     * @Route("admin/service/ajouter",name="Ajouterservice")
     */
    function Ajout(Request $request){
        $service=new Service();
        $form=$this->createForm(ServiceType::class,$service);
        $form->add("ajouter",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em=$this->getDoctrine()->getManager();
            $em->persist($service);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherservice");
        }
        return $this->render('service/ajout.html.twig',['f'=>$form->createView()]);
    }

    /**
     * @Route("admin/service/update/{id}",name="updateservice")
     */
    function update($id,ServiceRepository $repo,Request $request){
        $service=$repo->find($id) ;
        $form=$this->createForm(ServiceType::class,$service);
        $form->add("update",SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()&&$form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();//maj de la BD
            return $this->redirectToRoute("afficherservice");
        }

        return $this->render("service/update.html.twig",['f'=>$form->createView()]);

    }
    /**
     * @Route("admin/service/details/{id}",name="detailservice")
     */
    public function affichedetails($id){
        $repo=$this->getDoctrine()->getRepository(Service::class)->find($id);
        return $this->render('service/details.html.twig',['service'=>$repo]);
    }
    /**
     * @Route("admin/service/affiche", name="afficherservice")
     */
    public function affiche(ServiceRepository $repo,Request $request,PaginatorInterface $paginator){
        $categories=$this->getDoctrine()->getRepository(CategorieService::class)->findAll();
        $serv=$repo->findAll();
        /*$paginator=$this->get('knp_paginator');
        $services =$paginator->paginate(
            $serv,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',3));*/
        return $this->render('service/affiche.html.twig',['services'=>$serv,"categories"=>$categories]);
    }




    /**
     * @Route("service/details/{id}",name="service")
     */
    public function service($id,Request $request ,RateRepository $repo){
        $service=$this->getDoctrine()->getRepository(Service::class)->find($id);
        $reporate=$this->getDoctrine()->getRepository(Rate::class);
        $avg=$reporate->averageRatingDQL($id);
        $count=$reporate->sumrating($id);

        return $this->render('service/service.html.twig',['service'=>$service,
            'avg'=>$avg,
            'nbrate'=>$count

        ]);
    }
    /**
     * @Route("service/details/rateme/{id}",name="rateService")
     */
    public function rateme($id,Request $request){

        $reporate=$this->getDoctrine()->getRepository(Rate::class);
        $service=$this->getDoctrine()->getRepository(Service::class)->find($id);
        $avg=$reporate->averageRatingDQL($id);
        $count=$reporate->sumrating($id);
        $rate=new Rate();
        $manager=$this->getDoctrine()->getManager();
        $rate->setService($service);
        $star=rand(1,5);
        $rate->setUser($this->getUser());
        $rate->setStar($star);
        $manager->persist($rate);
        $manager->flush();
        return $this->render('service/service.html.twig',['service'=>$service,
            'avg'=>$avg,
            'nbrate'=>$count

        ]);
    }
}


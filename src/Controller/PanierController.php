<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\ServiceRepository;
use http\Env\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     * @param SessionInterface $session
     * @param ProduitRepository $produitRepository
     * @return
     */
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $i = 0;
        $panier = $session->get('panier', []);
        $panierwithData = [];
        foreach ($panier as $id => $quantity){
            $panierwithData [] = [
                'produit' => $produitRepository->find($id),
                'quantity' => $quantity
            ];
            $i++;
        }
        $session->set('number', $i);
        $total = 0;
        foreach ($panierwithData as $item){
            $totalitem = $item['produit']->getPrix() * $item['quantity'];
            $total +=$totalitem;
        }
        $session->set('total', $total);
        return $this->render('panier/index.html.twig', [
            'items' => $panierwithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/panierservice", name="panierservice")
     * @param SessionInterface $session
     * @return
     */
    public function IndexService(SessionInterface $session, ServiceRepository $repository)
    {
        $i = 0;
        $panier = $session->get('panier2', []);
        $panierwithData = [];
        foreach ($panier as $id => $quantity){
            $panierwithData [] = [
                'produit' => $repository->find($id),
                'quantity' => $quantity
            ];
            $i++;
        }
        $session->set('number', $i);
        $total = 0;
        foreach ($panierwithData as $item){
            $totalitem = $item['produit']->getPrix() * $item['quantity'];
            $total +=$totalitem;
        }
        $session->set('total', $total);
        return $this->render('panier/index2.html.twig', [
            'items' => $panierwithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/panier/add/{id}", name="panier_add")
     * @param $id
     * @param SessionInterface $session
     */
    public function add($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if(!empty($panier[$id])) {
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/panier2/add/{id}", name="panier_addd")
     * @param $id
     * @param SessionInterface $session
     */
    public function addd($id, SessionInterface $session)
    {
        $panier = $session->get('panier2', []);
        if(!empty($panier[$id])) {
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }
        $session->set('panier2', $panier);
        return $this->redirectToRoute("panierservice");
    }

    /**
     * @Route("/panier/remove/{id}", name="cart_remove")
     * @param $id
     * @param SessionInterface $session
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute("panier");
    }
}
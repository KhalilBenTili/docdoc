<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Paiement;
use App\Form\PaiementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /**
     * @Route("/commande/ajouter/{Prix_Total}/{userid}",name="Ajout_commande")
     * @param $Prix_Total
     * @return RedirectResponse|Response
     */
    function Ajout($Prix_Total, SessionInterface $session, $userid)
    {
        $commande=new Commande();
        $session->set('prix', $Prix_Total);
        $session->set('id_user', $userid );
        $em=$this->getDoctrine()->getManager();
        $commande->setPrixTotal($Prix_Total);
        $em->persist($commande);
        $em->flush();
        return $this->redirectToRoute("AjouterPaiement");
    }
}

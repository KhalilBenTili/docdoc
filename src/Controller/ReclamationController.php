<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="reclamation")
     */
    public function index(): Response
    {
        return $this->render('baseLR.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /**
     * @param ReclamationRepository $repo
     * @return Response
     *@Route("reclamation/AfficheReclamation",name="AfficheReclamation")
     */

    function affichead(ReclamationRepository $repo,PaginatorInterface $paginator,Request $request){
        $donnes=$repo->findAll();
        $reclamation = $paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),4
        );

        return $this->render("reclamation/AfficherAdmin.html.twig",
            ['reclamation'=>$reclamation]);
    }

    /**
     * @param ReclamationRepository $repo
     * @return Response
     * @Route("reclamation/AfficheAdminDQL")
     */
    function OrderByMotifDQL(ReclamationRepository $repo){
        $reclamation=$repo->OrderByMotif();
        return $this->render("reclamation/AfficherAdmin.html.twig",
            ['reclamation'=>$reclamation]);
    }





    /**
     * @param $id
     * @param ReclamationRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/Supp/{id}",name="Supprimer")
     */
    function Delete($id,ReclamationRepository $repo){
        $reclamation=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reclamation);//delete
        $em->flush();//mise à jour BD
        return $this->redirectToRoute('AfficheReclamation');
    }
    /**
     * @param $id
     * @param ReclamationRepository $repo
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/SuppUser/{id}",name="Supprimeru")
     */
    function Delete1($id,ReclamationRepository $repo){
        $reclamation=$repo->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($reclamation);//delete
        $em->flush();//mise à jour BD
        return $this->redirectToRoute('AfficheU');
    }

    /**
     * @IsGranted("ROLE_USER")
     * @param Request $req
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @Route("/user/AjouterReclamation",name="AjouterU")
     */
    function Ajouter(Request $req, FlashyNotifier $flashy){
        $rec=new Reclamation();
        $rec->setEtat(false);
        $form=$this->createForm(ReclamationType::class,$rec);
       // $form->add("Ajouter Reclamation",SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $rec->setUser($this->getUser());
            $em=$this->getDoctrine()->getManager();
            $em->persist($rec);
            $em->flush();
            $flashy->success('Réclamation ajouté');
            /*return $this->redirectToRoute('userr');*/
        }
        return $this->render("reclamation/Ajouter.html.twig",
        ['form'=>$form->createView()]);
    }




    /**
     * @param ReclamationRepository $repo
     * @param $id
     * @param Request $req
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     *@Route("reclamation/Modifier/{id}",name="ModifierRec")
     */
    function Update(ReclamationRepository $repo,$id,Request $req){
        $reclamation=$repo->find($id);
        $form=$this->createForm(ReclamationType::class,$reclamation);
        $form->add('Modifier',SubmitType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $em=$this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('AfficheU');
        }
        return $this->render("reclamation/Update.html.twig",
            ['form'=>$form->createView()]);


    }


   /* public function searchMotif(Request $request,NormalizerInterface $normalizer,ReclamationRepository $repository)
    {

        $requestString=$request->get('searchValue');
        $motif = $repository->findReclamationParMotif($requestString);
        $serializer = new Serializer(array(new DateTimeNormalizer('Y-m-d H:i:s')));
        $data=array();

        foreach ($motif as $v)
        {
            array_push($data,array("id"=>$v->getId(),"motif"=>$v->getMotif(),
                "publishDate"=>$serializer->normalize($v->getPublishDate()),"votes"=>count($v->getLikes()),"url"=>$v->getUrl(),"domaine"=>$v->getDomaine()));
        }


        return new JsonResponse($data);
    }*/

    /**
     * @Route("/reclamation/searchStudentx ", name="searchStudentxx")
     * @param Request $request
     * @param NormalizerInterface $Normalizer
     * @return Response
     * @throws ExceptionInterface
     */
    public function searchReclamation(Request $request,NormalizerInterface $Normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(Reclamation::class);
        $requestString=$request->get('searchValue');
        $reclamation = $repository->findReclamationBymotif($requestString);
        $jsonContent = $Normalizer->normalize($reclamation, 'json',['groups'=>'reclamation']);
        $retour = json_encode($jsonContent);
        return new JsonResponse($jsonContent);

    }
}

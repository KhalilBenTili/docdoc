<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commentaires;
use App\Entity\CategorieArticle;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use DateTime;
use DateTimeZone;
use Error;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('article/article.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/admin/article/affiche",name="affichercatarticle")
     */
    public function affiche()
    {
        $repo = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('article/affiche.html.twig', ['article' => $repo]);
    }

    /**
     * @Route("/admin/article/details/{id}",name="detailscatarticle")
     */
    public function affichedetails($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/details.html.twig', ['article' => $repo]);
    }

    /**
     * @Route("/catarticle/{id}/{user_id}", name="catarticle")
     */
    public function montrer($id, Request $request, FlashyNotifier $flashy, $user_id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $categorie = $this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);
        $em = $this->getDoctrine()->getManager();
        $con = $em->getConnection();
        $sql = 'UPDATE article SET nbvue=nbvue+1 WHERE id=' . $id;
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $comment = new Commentaires;
        $commentForm = $this->createForm(CommentairesType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setArticle($article);
            $comment->setUser($user);

            $parentid = $commentForm->get("parent_id")->getData();

            $em = $this->getDoctrine()->getManager();

            if ($parentid != null) {
                $parent = $em->getRepository(Commentaires::class)->find($parentid);
            }

            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();
            $flashy->success('votre commentaire a été ajouté');
            return $this->redirectToRoute('catarticle', ['id' => $article->getId(), 'user_id' => $user->getId()]);
        }


        return $this->render('article/index.html.twig', [
            'article' => $article,
            'categories' => $categorie,
            'user' => $user,
            'commentForm' => $commentForm->createView()]);


    }

    /**
     * @Route("admin/article/deletecomment/{comment_id}",name="deletecoarticle")
     */

    public function deleteComment($comment_id, CommentairesRepository $repo, FlashyNotifier $flashy)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $repo->find($comment_id);
        $em->remove($comment);
        $em->flush();
        $flashy->success('commentaire effacé');
        return $this->redirectToRoute('affichercatarticle');
    }


    /**
     * @Route("/blog",name="afficherblog")
     */
    public function afficheblog(Request $request, PaginatorInterface $paginator, ArticleRepository $repository)
    {
        $categorie = $this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();
        $donnees = $this->getDoctrine()->getRepository(Article::class)->findBy([],
            ['created_at' => 'desc']);
        $top = $this->getDoctrine()->getRepository(Article::class)->recommended();
        $article = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            2
        );

        return $this->render('blog/blog.html.twig', ['articles' => $article, 'categories' => $categorie, 'top' => $top]);
    }

    /**
     * @Route("/blog/afficheMobile/",name="afficherBlogMobile")
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function afficheBlogMobile(NormalizerInterface $normalizer)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        $json = $normalizer->normalize($articles, 'json', ['groups' => 'article']);
        return new Response(json_encode($json));
    }

    /**
     * @Route("/catArticle/afficheMobile/{id}",name="afficherCatArticleMobile")
     * @param NormalizerInterface $normalizer
     * @return Response
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function afficheCatArticleMobile($id, NormalizerInterface $normalizer)
    {
        $repo = $this->getDoctrine()->getRepository(CategorieArticle::class);
        $categorie = $repo->find($id);
        $json = $normalizer->normalize($categorie, 'json', ['groups' => 'catarticle']);
        return new Response(json_encode($json));
    }

    /**
     * @Route("admin/article/delete/{id}",name="deletecatarticle")
     */

    public function delete($id, ArticleRepository $repo, FlashyNotifier $flashy)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $repo->find($id);
        $em->remove($article);
        $em->flush();
        $flashy->success('article effacé');
        return $this->redirectToRoute('affichercatarticle');
    }

    /**
     * @Route("/admin/article/ajouter",name="Ajoutercatarticle")
     */
    function Ajout(Request $request, FlashyNotifier $flashy)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $flashy->success('article ajouté');
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);//insert into
            $em->flush();//maj de la BD
            return $this->redirectToRoute("affichercatarticle");
        }
        return $this->render('article/ajout.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("admin/article/update/{id}",name="updatecatarticle")
     * @param $id
     * @param ArticleRepository $repo
     * @param Request $request
     * @param $article
     * @return RedirectResponse|Response
     */

    function update($id, ArticleRepository $repo, Request $request, FlashyNotifier $flashy)
    {
        $article = $repo->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('article mis à jour');
            return $this->redirectToRoute('affichercatarticle');
        }

        return $this->render('article/update.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/article/like/{id}/{user_id}", name="jaime")
     * @param $id
     * @param ArticleRepository $repo
     * @param $article
     * @return RedirectResponse|Response
     */
    public function like($id, ArticleRepository $repo, $user_id, Request $request, FlashyNotifier $flashy)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $repo->find($id);
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);
        $categorie = $this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();

        $article->setNblike($article->getNblike() + 1);
        $em->flush();
        $comment = new Commentaires;

        $commentForm = $this->createForm(CommentairesType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setArticle($article);
            $comment->setUser($user);

            $parentid = $commentForm->get("parent_id")->getData();

            $em = $this->getDoctrine()->getManager();

            if ($parentid != null) {
                $parent = $em->getRepository(Commentaires::class)->find($parentid);
            }

            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();
            $flashy->success('votre commentaire a été ajouté');
            return $this->redirectToRoute('catarticle', ['id' => $article->getId(), 'user_id' => $user->getId()]);
        }
        return $this->render("article/index.html.twig", ['article' => $article, 'categories' => $categorie, 'user' => $user, 'commentForm' => $commentForm->createView()]);
    }

    /**
     * @Route ("/article/dislike/{id}/{user_id}", name="jaimepas")
     * @param $id
     * @param ArticleRepository $repo
     * @param $article
     * @return RedirectResponse|Response
     */
    public function dislike($id, ArticleRepository $repo, Request $request, FlashyNotifier $flashy, $user_id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $repo->find($id);
        $user = $this->getDoctrine()->getRepository(User::class)->find($user_id);
        $categorie = $this->getDoctrine()->getRepository(CategorieArticle::class)->findAll();

        $article->setNbdislike($article->getNbdislike() + 1);
        $em->flush();
        $comment = new Commentaires;

        $commentForm = $this->createForm(CommentairesType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setArticle($article);
            $comment->setUser($user);

            $parentid = $commentForm->get("parent_id")->getData();

            $em = $this->getDoctrine()->getManager();

            if ($parentid != null) {
                $parent = $em->getRepository(Commentaires::class)->find($parentid);
            }

            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();
            $flashy->success('votre commentaire a été ajouté');
            return $this->redirectToRoute('catarticle', ['id' => $article->getId(), 'user_id' => $user->getId()]);
        }
        return $this->render("article/index.html.twig", ['article' => $article, 'categories' => $categorie, 'user' => $user, 'commentForm' => $commentForm->createView()]);
    }

    /**
     * @Route ("/commentaire/afficheMobile")
     */
    public function afficherCommentaireMobile(Request $request)
    {
        $articleId = $request->get("articleId");
        $commentaires = $this->getDoctrine()->getRepository(Commentaires::class)->findAll();

        $jsonContent = null;
        $i = 0;
        foreach ($commentaires as $commentaire) {
            $jsonContent[$i]['id'] = $commentaire->getId();
            $jsonContent[$i]['contenu'] = $commentaire->getContenu();
            $jsonContent[$i]['articleId'] = $commentaire->getArticle()->getId();

            $i++;
        }

        if ($commentaires) {
            return new Response(json_encode($jsonContent));
        } else {
            return new Response(null);
        }

    }

    /**
     * @Route ("/commentaire/ajouterMobile")
     * @throws Exception
     */
    public function ajouterCommentaire(Request $request)
    {
        $contenu = $request->get("contenu");
        $articleId = (int)$request->get("articleId");

        $commentaire = new Commentaires();
        $commentaire->setUser($this->getDoctrine()->getRepository(User::class)->find(1));
        $commentaire->setArticle($this->getDoctrine()->getRepository(Article::class)->find($articleId));
        $commentaire->setCreatedAt(new DateTime('now', new DateTimeZone('Africa/Tunis')));
        $commentaire->setContenu($contenu);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($commentaire);
        $manager->flush();

        return new JsonResponse("commentaire ajouté");
    }

    /**
     * @Route ("/blog/tri",name="triArticle")
     */
//    public function tri(ArticleRepository $repository , Request $request)
//    {
//
//        if (isset($_POST['tri']))
//        {
//            $choix = $_POST['tri'];
//            if ($choix=='A-Z')
//            {
//                $article=$repository->OrderByNameC();
//                return $this->render("blog/blog.html.twig",['article'=>$article]);
//            }
//            elseif ($choix=='Z-A')
//            {
//                $article=$repository->OrderByNameD();
//                return $this->render("blog/blog.html.twig",['article'=>$article]);
//            }
//
//
//        }
//
//    }


}
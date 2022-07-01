<?php

namespace App\Controller;

use App\classe\Search;
use App\Entity\Article;
use App\Form\SearchType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

#[Route('/article')]
class ArticleController extends AbstractController

{


    #[Route('/', name: 'article')]
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity

            ];
        }
        $total_panier = 0;
        foreach ($panierWithData as $item) {

            $total_panier = $total_panier + $item['quantity'];
        }
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'items' => $panierWithData,
            'total_panier' => $total_panier
        ]);
    }

    #[Route('/all', name: 'articleall')]
    public function all(Request $request, PaginatorInterface $paginator, SessionInterface $session, ArticleRepository $articleRepository): Response
    {


        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity

            ];
        }
        $total_panier = 0;
        foreach ($panierWithData as $item) {

            $total_panier = $total_panier + $item['quantity'];
        }
        $search =new Search();
        $form= $this->createForm(SearchType::class,$search);

        $form->handleRequest($request);
        if ($form->isSubmitted() &&$form->isValid()){
            $allarticles = $this->getDoctrine()->getRepository(Article::class)->findWithSearch($search);
            $articles = $paginator->paginate($allarticles, $request->query->getInt('page', 1), 5);
        }
        $allarticles = $this->getDoctrine()->getRepository(Article::class)->findWithSearch($search);
        $articles = $paginator->paginate($allarticles, $request->query->getInt('page', 1), 5);

        return $this->render('article/lister.html.twig', [
            'articles' => $articles,
            'items' => $panierWithData,
            'total_panier' => $total_panier,
            'form'=>$form->createView()

        ]);
    }

    #[Route('/show/{id}', name: 'article_show')]
    public function show($id, SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $panier = $session->get('panier', []);

        $panierWithData = [];

        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity

            ];
        }
        $total_panier = 0;
        foreach ($panierWithData as $item) {

            $total_panier = $total_panier + $item['quantity'];
        }
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'items' => $panierWithData,
            'total_panier' => $total_panier
        ]);
    }
}



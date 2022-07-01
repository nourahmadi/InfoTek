<?php



namespace App\Controller;

use App\classe\panier;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'panier')]
    public function index(SessionInterface $session, ArticleRepository $articleRepository,panier $panier): Response
    {
       $panierWithData=$panier->getFull($articleRepository,$session);
        $total = 0;
        foreach ($panierWithData as $item) {
            $totalitem = $item['article']->getPrice() * $item['quantity'];
            $total = $total + $totalitem;
        }
        $total_panier = 0;
        foreach ($panierWithData as $item) {

            $total_panier = $total_panier + $item['quantity'];
        }
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'total_panier'=>$total_panier
        ]);
    }

    #[Route('/panier/add/{id}/{route}', name: 'panier_add')]
    public function add($id, SessionInterface $session,Request $request,$route)
    {

        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }


        $session->set('panier', $panier);
        $this->addFlash('success', 'Article added');

        return $this->redirectToRoute($route);

    }
    #[Route('/panier/del/{id}', name: 'panier_del')]
    public function remove($id, SessionInterface $session)
    {

        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }


        $session->set('panier', $panier);
        return $this->redirectToRoute('panier');

    }
    #[Route('/panier/commander/{nom}/{quantité}', name: 'commander')]
    public function commande($nom,$quantité, SessionInterface $session){




    }






}

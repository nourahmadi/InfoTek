<?php
namespace App\classe;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class panier
{


    public function get(SessionInterface $session){
        return $session->get('panier', []);
    }
    public function getFull(ArticleRepository $articleRepository,SessionInterface $session){

        $panierWithData = [];

        foreach ($this->get($session) as $id => $quantity) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierWithData;
    }

}
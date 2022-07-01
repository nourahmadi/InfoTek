<?php

namespace App\Controller;

use App\classe\panier;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'order')]
    public function index(panier $panier, ArticleRepository $articleRepository, SessionInterface $session, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {
            return $this->redirectToRoute("adresse");
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);


        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $panier->getFull($articleRepository, $session)
        ]);
    }

    #[Route('/commande/recap', name: 'order_recap')]
    public function add(panier $panier, ArticleRepository $articleRepository, SessionInterface $session, Request $request): Response
    {
        $YOUR_DOMAIN ='http://127.0.0.1:8000';
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $date = new \DateTime();
            $transporteur = $form->get('Transporteurs')->getData();
            $adresse = $form->get('addresses')->getData();
            $adresse_contenue = $adresse->getAdresse() . ' ' . $adresse->getCity() . ' ' . ' ' . $adresse->getPays() . $adresse->getPhone();


            $order = new Order();
            $reference=$date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setTransporteur($transporteur->getName());
            $order->setPrixTransporteur($transporteur->getPrice());
            $order->setAdresse($adresse_contenue);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);


            foreach ($panier->getFull($articleRepository, $session) as $produit) {
                $orderdetails = new OrderDetails();
                $orderdetails->setMyOrder($order);
                $orderdetails->setProduct($produit['article']->getTitle());
                $orderdetails->setQuantite($produit['quantity']);
                $orderdetails->setPrice($produit['article']->getPrice());
                $orderdetails->setTotal($produit['article']->getPrice() * $produit['quantity']);
                $this->entityManager->persist($orderdetails);

            }



            $this->entityManager->flush();




            return $this->render('order/recap.html.twig', [
                'cart' => $panier->getFull($articleRepository, $session),
                'livraison' => $transporteur,
                'ref'=>$order->getReference()
            ]);
        }
        return $this->redirectToRoute('panier');
    }
}

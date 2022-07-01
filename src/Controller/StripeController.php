<?php

namespace App\Controller;

use App\classe\panier;
use App\Entity\Order;

use App\Entity\Transporteurs;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe')]
    public function index(EntityManagerInterface $em,$reference): Response
    {
       $order= $em->getRepository(Order::class)->findOneByReference($reference);
       $transporteur=$em->getRepository(Transporteurs::class)->findOneByName($order->getTransporteur());


        $products_for_stripe=[];
        foreach ($order->getOrderDetails()->getValues() as $produit) {

            $products_for_stripe[]=[
                'price_data'=>[
                    'currency'=>'usd',
                    'unit_amount'=>$produit->getPrice(),
                    'product_data'=>[
                        'name'=>$produit->getProduct(),

                    ],
                ],
                'quantity'=>$produit->getQuantite(),
            ];

        }
        $products_for_stripe[]=[
            'price_data'=>[
                'currency'=>'usd',
                'unit_amount'=>$transporteur->getPrice()*100,
                'product_data'=>[
                    'name'=>"frais de livraison",

                ],
            ],
            'quantity'=>1,
        ];

        Stripe::setApiKey('sk_test_51KM1V5H517Qug8LwEjUQi2NJHNEyu3jlzS0UdqEUtotC22l0N2ooZ8MDNQs638un6En75Bucju4utx76uSBs5RB600vYWe2jzP');
        $checkout_session =Session::create([
            'payment_method_types'=>['card'],
            'line_items'=>[
                $products_for_stripe
            ],
            'mode'=>'payment',
            'success_url'=>$this->generateUrl('success_url',[],
            UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url'=>$this->generateUrl('cancel_url',[],
                UrlGeneratorInterface::ABSOLUTE_URL),
        ]);


        return  $this->redirect($checkout_session->url,303);
    }
    #[Route('/success_url', name: 'success_url')]
    public function success_url(): Response
    {
        return $this->render('order/success.html.twig');
    }
    #[Route('/cancel_url', name: 'cancel_url')]
    public function cancel_url(): Response
    {
        return $this->render('order/cancel.html.twig');
    }
}

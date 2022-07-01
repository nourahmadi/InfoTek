<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Order;
use App\Entity\Transporteurs;
use App\Entity\User1;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;



class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->count([]);
        $users = $this->getDoctrine()->getRepository(User1::class)->count([]);

        return $this->render('admin\welcome.html.twig', [
            'articles' => $articles,
            'users' => $users,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Info Teck');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('utilisateurs', 'fas fa-user',User1::class);
        yield MenuItem::linkToCrud('Categorys', 'fas fa-list',Category::class);
        yield MenuItem::linkToCrud('Articles', 'fas fa-tag',Article::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fas fa-truck',Transporteurs::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-shopping-cart',Order::class);


    }
}

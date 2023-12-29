<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Categorie;
use App\Controller\Admin\AvisCrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class AdminController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Vérifie qu'un utilisateur est connecté et qu'il ait le rôle admin
        if ($this->getUser() && $this->IsGranted('ROLE_ADMIN')) {
            // Instancie un adminUrlGenerator
            $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

            // Génrère une url avec le generator et redirige vers celle çi
            return $this->redirect($adminUrlGenerator->setController(AvisCrudController::class)->generateUrl());
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    public function configureDashboard(): Dashboard
    {
        // Défini le titre en haut de page
        return Dashboard::new()
            ->setTitle('SfECommerce');
    }

    public function configureMenuItems(): iterable
    {
        // Construit le menu
        yield MenuItem::section('Administration', 'fa fa-home');
        yield MenuItem::linkToCrud('Avis', 'fa-regular fa-comment', Avis::class);
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Categorie::class)
        ->setQueryParameter('filters[id][value]', '3')
        ->setQueryParameter('filters[id][comparison]', '>');
        yield MenuItem::linkToCrud('Commandes', 'fa-solid fa-basket-shopping', Commande::class)
        ->setQueryParameter('filters[etat][value]', 'panier')
        ->setQueryParameter('filters[etat][comparison]', '!=');
        yield MenuItem::linkToCrud('Marques', 'fa-solid fa-city', Marque::class);
        yield MenuItem::linkToCrud('Produits', 'fa-solid fa-computer', Produit::class);
    }

    public function configureAssets(): Assets
    {
        // Charge un fichier css personnalisé pour changer le style de l'administration
        return Assets::new()->addCssFile('css/admin.css');
    }
}

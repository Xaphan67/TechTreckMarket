<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\MarqueRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(CategorieRepository $categorieRepository, ProduitRepository $produitRepository, MarqueRepository $marqueRepository): Response
    {
        // On récupère les sections qui doivent s'afficher sur la page d'accueil
        $sections = $categorieRepository->findBy([
            'categorieParent' => null,
            'accueil' => true
        ]);

        // On récupère les nouveaux produits pour chaque section
        $nouveautes = [];
        foreach ($sections as $section) {
            $nouveautes[$section->getNom()] = $produitRepository->findBy([
                'categoriePrincipale' => $section,
                "archive" => false
            ], [
                "dateLancement" => "DESC"
            ], 3);
        };

        // On récupère la liste de toutes les catégories qui doivent s'afficher sur la page d'accueil
        $categories = $categorieRepository->findBy([
            'accueil' => true
        ]);

        // On récupère la liste des marques
        $marques = $marqueRepository->findAll();

        // On mélange les marques
        shuffle($marques);

        // On récupère uniquement les 9 premières marques du tableau
        $marques = array_slice($marques, 0, 9);

        return $this->render('accueil/index.html.twig', [
            'sections' => $sections,
            'nouveautes' => $nouveautes,
            'categories' => $categories,
            'marques' => $marques
        ]);
    }

    #[route('/cgdv', name: 'app_cgdv')]
    public function cgv() {
        return $this->render('rgpd/cgdv.html.twig');
    }

    #[route('/mentionsLegales', name: 'app_mentions_legales')]
    public function legalInformations() {
        return $this->render('rgpd/mentionsLegales.html.twig');
    }

    #[route('/donnesPersonnelles', name: 'app_donnees_personnelles')]
    public function personal() {
        return $this->render('rgpd/donneesPersonnelles.html.twig');
    }
}

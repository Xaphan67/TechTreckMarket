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
        // On récupère les 3 catégories principales : Ordinateurs, Composants et Périphériques
        $categorieOrdinateurs = $categorieRepository->findOneBy(['nom' => "Ordinateurs"]);
        $categorieComposants = $categorieRepository->findOneBy(['nom' => "Composants"]);
        $categoriePeripheriques = $categorieRepository->findOneBy(['nom' => "Peripheriques"]);

        // On récupère la liste des 3 derniers produits de chaque catégorie principale
        $ordinateurs = $produitRepository->findBy([
            'categoriePrincipale' => $categorieOrdinateurs,
            "archive" => false
        ], [
            "dateLancement" => "DESC"
        ], 3);
        $composants = $produitRepository->findBy([
            'categoriePrincipale' => $categorieComposants,
            "archive" => false
        ], [
            "dateLancement" => "DESC"
        ], 3);
        $peripheriques = $produitRepository->findBy([
            'categoriePrincipale' => $categoriePeripheriques,
            "archive" => false
        ], [
            "dateLancement" => "DESC"
        ], 3);

        // On récupère les sections qui doivent s'afficher sur la page d'accueil
        $sections = $categorieRepository->findBy([
            'categorieParent' => null,
            'accueil' => true
        ]);

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
            'ordinateurs' => $ordinateurs,
            'composants' => $composants,
            'peripheriques' => $peripheriques,
            'sections' => $sections,
            'categories' => $categories,
            'marques' => $marques
        ]);
    }
}

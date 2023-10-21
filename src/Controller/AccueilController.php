<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(CategorieRepository $categorieRepository, ProduitRepository $produitRepository): Response
    {
        // On récupère les 3 catégories principales : Ordinateurs, Composants et Périphériques
        $categorieOrdinateurs = $categorieRepository->findOneBy(['nom' => "Ordinateurs"]);
        $categorieComposants = $categorieRepository->findOneBy(['nom' => "Composants"]);
        $categoriePeripheriques = $categorieRepository->findOneBy(['nom' => "Peripheriques"]);

        // On récupère la liste des 3 derniers produits de chaque catégorie principale
        $ordinateurs = $produitRepository->findBy(['categoriePrincipale' => $categorieOrdinateurs], ["dateLancement" => "DESC"], 3);
        $composants = $produitRepository->findBy(['categoriePrincipale' => $categorieComposants], ["dateLancement" => "DESC"], 3);
        $peripheriques = $produitRepository->findBy(['categoriePrincipale' => $categoriePeripheriques], ["dateLancement" => "DESC"], 3);

        return $this->render('accueil/index.html.twig', [
            'ordinateurs' => $ordinateurs,
            'composants' => $composants,
            'peripheriques' => $peripheriques
        ]);
    }
}

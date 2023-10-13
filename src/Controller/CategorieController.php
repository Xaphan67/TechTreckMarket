<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie/{id}', name: 'afficher_categorie')]
    public function show(Categorie $categorie = null, CategorieRepository $categorieRepository, EntityManagerInterface $entityManager): Response
    {

        // Récupère les catégories parentes à la catégorie actuelle pour pouvoir les afficher correctement dans le fil d'ariane via twig
        // Ceci permet une meilleure optimisation dans la vue en twig

        // Instancie un tableau vide
        $categoriesParent = [];

        // Récupère la valeur de la catégorie parente à la catégorie actuelle
        $parent = $categorie->getCategorieParent();

        // Si la catégorie parente n'est pas nulle, on l'ajoute au tableau et on récupère la valeur de la catégorie parente à cette catégorie
        while ($parent != null)
        {
            $categoriesParent[] = $parent;
            $parent = $parent->getCategorieParent();
        }

        return $this->render('categorie/index.html.twig', [
            'categoriesParent' => $categoriesParent,
            'categorie' => $categorie,
        ]);
    }
}

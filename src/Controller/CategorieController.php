<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\CssSelector\Parser\Shortcut\ElementParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie/{id}', name: 'afficher_categorie')]
    public function show(Categorie $categorie, ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Aucun produit ou marque par défaut
        $produits = null;
        $marques = [];

        // Si la catégorie n'a pas de sous catégories, on récupère
        // la liste des produits appartenant à la catégorie
        if (count($categorie->getSousCategories()) == 0)
        {
            $produits = $produitRepository->findBy(['categorie' => $categorie], ["designation" => "ASC"]);
            foreach ($produits as $produit)
            {
                if (array_key_exists($produit->getMarque()->getNom(), $marques))
                {
                    $marques[$produit->getMarque()->getNom()]++;
                }
                else
                {
                    $marques[$produit->getMarque()->getNom()] = 1;
                }

                // Crée la pagination pour la liste des produits
                $produitsPagination = $paginator->paginate(
                    $produits, // Contenu à paginer
                    $request->query->getInt('page', 1), // Page à afficher
                    10 // Limite par page
                );
            }
        }

        // Récupère les catégories parentes à la catégorie actuelle pour
        //pouvoir les afficher correctement dans le fil d'ariane via twig
        // Ceci permet une meilleure optimisation dans la vue en twig

        // Instancie un tableau vide
        $categoriesParent = [];

        // Récupère la valeur de la catégorie parente à la catégorie actuelle
        $parent = $categorie->getCategorieParent();

        // Si la catégorie parente n'est pas nulle, on l'ajoute au tableau et
        // on récupère la valeur de la catégorie parente à cette catégorie
        while ($parent != null)
        {
            $categoriesParent[] = $parent;
            $parent = $parent->getCategorieParent();
        }

        return $this->render('categorie/index.html.twig', [
            'categoriesParent' => $categoriesParent,
            'categorie' => $categorie,
            'produits' => $produitsPagination,
            'marques' => $marques
        ]);
    }
}

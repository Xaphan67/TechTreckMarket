<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\FiltresType;
use App\Form\SortProductsType;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    #[Route('/categorie/{id}', name: 'afficher_categorie')]
    public function show(Categorie $categorie, ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Initialisation des variables
        $produits = null;
        $produitsParMarque = [];
        $marques = [];
        $filtreForm = null;
        $filtres = false;

        // Si la catégorie n'a pas de sous catégories, on récupère
        // la liste des produits appartenant à la catégorie
        if (count($categorie->getSousCategories()) == 0) {
            $produits = $produitRepository->findBy([
                    'categorie' => $categorie,
                    "archive" => false
                ], [
                    "designation" => "ASC"
                ]);
            foreach ($produits as $produit) {
                if (array_key_exists($produit->getMarque()->getNom(), $produitsParMarque)) {
                    $produitsParMarque[$produit->getMarque()->getNom()]++;
                } else {
                    $produitsParMarque[$produit->getMarque()->getNom()] = 1;
                    $marques[$produit->getMarque()->getNom()] = $produit->getMarque()->getNom();
                }
            }

            // Instancie les formulaires
            $filtreForm = $this->createForm(FiltresType::class, null, ['marques' => $marques]);
            $filtreForm->handleRequest($request);

            // Vérifie que le formulaire est soumis et est valide
            if ($filtreForm->isSubmitted() && $filtreForm->isValid()) {
                // Récupère les informations du formulaire
                $formData = $filtreForm->getData();

                $triColonne = "designation";
                $triDirection = "ASC";

                if ($formData["tri"] == "marque") {
                    $triColonne = "marque";
                    $triDirection = "DESC";
                } else if ($formData["tri"] == "ASC") {
                    $triColonne = "prix";
                } else if ($formData["tri"] == "DESC") {
                    $triColonne = "prix";
                    $triDirection = "DESC";
                }

                // Récupère les produits en fonction des filtres spécifiés dans le formulaire
                $produits = $produitRepository->findByFilters(
                    $categorie->getNom(),
                    explode(" ", $formData["reference"]),
                    $formData["disponibilite"],
                    $formData["marques"],
                    $formData["prixMinimum"],
                    $formData["prixMaximum"],
                    $triColonne,
                    $triDirection);

                // Change filtres à vrai pour indiquer qu'un filtrage à été effectué
                $filtres = true;
            }

            // Crée la pagination pour la liste des produits
            $produitsPagination = $paginator->paginate(
                $produits, // Contenu à paginer
                $request->query->getInt('page', 1), // Page à afficher
                10 // Limite par page
            );

            $produits = $produitsPagination;
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
        while ($parent != null) {
            $categoriesParent[] = $parent;
            $parent = $parent->getCategorieParent();
        }

        return $this->render('categorie/index.html.twig', [
            'categoriesParent' => $categoriesParent,
            'categorie' => $categorie,
            'produits' => $produits,
            'marques' => $produitsParMarque,
            'filtresFormulaire' => $filtreForm,
            'filtres' => $filtres
        ]);
    }
}

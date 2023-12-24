<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/produit/{id}', name: 'voir_produit')]
    public function show(Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class);

        // Récupère les catégories parentes à la catégorie du produit pour
        //pouvoir les afficher correctement dans le fil d'ariane via twig
        // Ceci permet une meilleure optimisation dans la vue en twig

        // Instancie un tableau vide
        $categoriesParent = [];

        // Récupère la valeur de la catégorie parente à la catégorie actuelle
        $parent = $produit->getCategorie()->getCategorieParent();

        // Si la catégorie parente n'est pas nulle, on l'ajoute au tableau et
        // on récupère la valeur de la catégorie parente à cette catégorie
        while ($parent != null) {
            $categoriesParent[] = $parent;
            $parent = $parent->getCategorieParent();
        }

        return $this->render('produit/show.html.twig', [
            'categoriesParent' => $categoriesParent,
            'produit' => $produit,
            'formulaire' => $form
        ]);
    }
}

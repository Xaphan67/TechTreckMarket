<?php

namespace App\Controller;

use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\RecherchePrincipaleType;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    #[Route('/recherche', name: 'recherche_principale')]
    public function mainSearch(ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Instancie un formulaire de type RecherchePrincipale
        $form = $this->createForm(RecherchePrincipaleType::class);
        $form->handleRequest($request);

        // Vérifie que le formulaire est soumis et est valide
        if($form->isSubmitted() && $form->isValid()) {
            // Enregistre l'url d'entrée dans une variable
            $url = $request->headers->get('referer');

            // Enregistre le contenu de la recherche dans une variable
            $recherche = $form->getData()["recherche"];

            // Récupère tout les produits dont la marque ou la designation contiennent la valeur entrée dans le champ de recherche
            $produits = $produitRepository->findByTrademarkOrName($recherche);

            // Crée la pagination pour la liste des produits
            $produitsPagination = $paginator->paginate(
                $produits, // Contenu à paginer
                $request->query->getInt('page', 1), // Page à afficher
                10 // Limite par page
            );

            $produits = $produitsPagination;

            return $this->render('recherche/search.html.twig', [
                'recherche' => $recherche,
                'produits' => $produits,
                'url' => $url
            ]);
        }

        return $this->render('recherche/mainSearchForm.html.twig', [
            'formulaire' => $form
        ]);
    }

    #[Route('/recherche/{marque}/source={produit}', name:'recherche_marque')]
    public function trademarkSearch(Marque $marque, Produit $produit, ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère tout les produits dont la marque équivant à celle passée en paramettre
        $produits = $produitRepository->findBy(['marque' => $marque], ["designation" => "ASC"]);

        // Crée la pagination pour la liste des produits
        $produitsPagination = $paginator->paginate(
            $produits, // Contenu à paginer
            $request->query->getInt('page', 1), // Page à afficher
            10 // Limite par page
        );

        $produits = $produitsPagination;

        return $this->render('recherche/search.html.twig', [
            'recherche' => $marque->getNom(),
            'produits' => $produits,
            'url' => "/produit/" . $produit->getId()
        ]);
    }

    #[Route('/recherche/{marque}/{categorie}/source={produit}', name:'recherche_categorie_marque')]
    public function trademarkCategorySearch(Marque $marque, Categorie $categorie, Produit $produit, ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère tout les produits dont la marque équivant à celle passée en paramettre
        $produits = $produitRepository->findBy([
            'marque' => $marque,
            'categorie' => $categorie],
            ["designation" => "ASC"]);

        // Crée la pagination pour la liste des produits
        $produitsPagination = $paginator->paginate(
            $produits, // Contenu à paginer
            $request->query->getInt('page', 1), // Page à afficher
            10 // Limite par page
        );

        $produits = $produitsPagination;

        return $this->render('recherche/search.html.twig', [
            'recherche' => $categorie->getNom() . " " . $marque->getNom(),
            'produits' => $produits,
            'url' => "/produit/" . $produit->getId()
        ]);
    }
}

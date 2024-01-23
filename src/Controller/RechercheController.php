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
    #[Route('recherche/{recherche?}', name: 'recherche_principale')]
    public function mainSearch(?string $recherche = "", ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Instancie un formulaire de type RecherchePrincipale
        $form = $this->createForm(RecherchePrincipaleType::class);
        $form->handleRequest($request);

        // Enregistre l'url d'entrée dans une variable
        $url = $request->headers->get('referer');

        // Vérifie que le formulaire est soumis
        if($form->isSubmitted()) {
            // Vérifie que le formulaire est valide
            if ($form->isValid()) {
                    // Re-exécute la fonction avec le paramettre $recherche ayant la valeur de la recherche et visible dans l'url
                    $request->getSession()->set('recherche', $form->getData()["recherche"]);
                    return $this->redirectToRoute('recherche_principale', ['recherche' => $form->getData()["recherche"]]);
            } else {
                // Affiche un message
                $this->addFlash('danger', 'Veuillez entrer au moins un terme de recherche.');

                // Redirige vers l'url d'entrée
                return $this->redirect($url);
            }
        } else {
            $recherche =  $request->getSession()->get('recherche');

            // Récupère tout les produits dont la marque ou la designation contiennent la valeur entrée dans le champ de recherche
            $produits = $produitRepository->findByTrademarkOrName(explode(" ", $recherche));

            // Crée la pagination pour la liste des produits
            $produitsPagination = $paginator->paginate(
                $produits, // Contenu à paginer
                $request->query->getInt('page', 1), // Page à afficher
                10 // Limite par page
            );

            $produits = $produitsPagination;
        }

        return $this->render('recherche/search.html.twig', [
            'recherche' => $recherche,
            'produits' => $produits,
            'url' => $url
        ]);
    }

    #[Route('/recherche-formulaire', name: 'recherche_principale_formulaire')]
    public function searchForm(): Response
    {
        // Instancie un formulaire de type RecherchePrincipale
        $form = $this->createForm(RecherchePrincipaleType::class);

        return $this->render('recherche/_mainSearchForm.html.twig', [
            'formulaire' => $form
        ]);
    }

    #[Route('/recherche/{marque}/source={produit}', name:'recherche_marque')]
    public function trademarkSearch(Marque $marque, Produit $produit, ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Récupère tout les produits dont la marque équivant à celle passée en paramettre
        $produits = $produitRepository->findBy([
            'marque' => $marque,
            "archive" => false
        ], [
            "designation" => "ASC"
        ]);

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
            'categorie' => $categorie,
            "archive" => false],
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

<?php

namespace App\Controller;

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
                'produits' => $produits
            ]);
        }

        return $this->render('recherche/mainSearchForm.html.twig', [
            'formulaire' => $form
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\CategorieRepository;
use App\Repository\ProduitRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfigurateurController extends AbstractController
{
    #[Route('/configurateur/{etape}', name: 'configurateur')]
    public function index(int $etape, CategorieRepository $categorieRepository, ProduitRepository $produitRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Séléctionne la catégorie des produits qui seront proposés en fonction de l'étape actuelle
        $nomCategorie = null;
        switch ($etape) {
            case 1:
                $nomCategorie = "Cartes mère";
                break;
            case 2:
                $nomCategorie = "Cartes mère";
                break;
            case 3:
                $nomCategorie = "Cartes mère";
                break;
            case 4:
                $nomCategorie = "Cartes mère";
                break;
            case 5:
                $nomCategorie = "Cartes mère";
                break;
            case 6:
                $nomCategorie = "Cartes mère";
                break;
            case 7:
                $nomCategorie = "Cartes mère";
                break;
            case 8:
                $nomCategorie = "Cartes mère";
                break;
        }

        $categorie = $categorieRepository->findOneBy(['nom' => $nomCategorie]);

        // Récupère les produits correspondant à la catégorie
        $produits = $produitRepository->findBy(['categorie' => $categorie]);

        // Crée la pagination pour la liste des produits
        $produitsPagination = $paginator->paginate(
            $produits, // Contenu à paginer
            $request->query->getInt('page', 1), // Page à afficher
            10 // Limite par page
        );

        $produits = $produitsPagination;

        // Calcul le prix total de la configuration
        $totalConfiguration = 0;
        foreach ($request->getSession()->get('configuration') as $produit) {
            $totalConfiguration += $produit->getprix();
        }

        return $this->render('configurateur/index.html.twig', [
            'etape' => $etape,
            'produits' => $produits,
            'configuration' =>$request->getSession()->get('configuration'),
            'totalConfiguration' => $totalConfiguration
        ]);
    }

    #[Route('/configurateur/ajout/{id}/{etape}', name: 'ajout_configurateur')]
    public function add(Produit $produit, int $etape, Request $request)
    {
        // Initialise les vraiables
        $configuration = [];

        // Vérifie si l'utilisateur à déja une configuration en session
        if ($request->getSession()->get('configuration')) {
            // Récupère la configuration stockée en session
            $configuration = $request->getSession()->get('configuration');
        }

        // Ajoute le produit à la configuration
        $configuration[] = $produit;

        // Stocke la configuration en session
        $request->getSession()->set('configuration', $configuration);

        return $this->redirectToRoute('configurateur', ['etape' => $etape]);
    }
}

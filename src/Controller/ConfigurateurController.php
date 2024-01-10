<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitCaracteristiqueTechniqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConfigurateurController extends AbstractController
{
    #[Route('/configurateur/{etape}', name: 'configurateur')]
    public function index(int $etape, CategorieRepository $categorieRepository, ProduitRepository $produitRepository, ProduitCaracteristiqueTechniqueRepository $produitCtRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Initialisation des variables
        $nomCategorie = null;
        $titreEtape = null;
        $produits = [];

        // Séléctionne la catégorie des produits qui seront proposés en fonction de l'étape actuelle
        switch ($etape) {
            case 1:
                $nomCategorie = "Boîtiers";
                $titreEtape = "boîtier";
                break;
            case 2:
                $nomCategorie = "Cartes mère";
                $titreEtape = "carte mère";
                break;
            case 3:
                $nomCategorie = "Processeurs";
                $titreEtape = "processeur";
                break;
            case 4:
                $nomCategorie = "Refroidissement";
                $titreEtape = "ventirad";
                break;
            case 5:
                $nomCategorie = "Mémoire";
                $titreEtape = "mémoire";
                break;
            case 6:
                $nomCategorie = "Cartes graphique";
                $titreEtape = "carte graphique";
                break;
            case 7:
                $nomCategorie = "Stockage";
                $titreEtape = "stockage";
                break;
            case 8:
                $nomCategorie = "Alimentations";
                $titreEtape = "alimentation";
                break;
        }

        // Récupère la catégorie correspondante à l'étape
        $categorie = $categorieRepository->findOneBy(['nom' => $nomCategorie]);

        // Vérifie s'il y a des sous-catégories
        if (count($categorie->getSousCategories()) > 0) {
            // Si oui, ajoute tout les produit de chaque sous-catégorie
            foreach ($categorie->getSousCategories() as $sousCategorie) {
                $produits += $produitRepository->findBy(['categorie' => $sousCategorie]);
            }
        } else {
            // Récupère les produits correspondant à la catégorie
            $produits = $produitRepository->findBy(['categorie' => $categorie]);
        }

        // Vérifications propres à chaque étape à partir de l'étape 2
        if ($etape >= 2) {
            $produitsCompatibles = [];
            switch ($etape) {
                case 2:
                $produitsCompatibles += $this->checkCompatibility($request, $produitCtRepository, $produits, 1, "Format de carte mère");
                break;
                case 3:
                $produitsCompatibles += $this->checkCompatibility($request, $produitCtRepository, $produits, 2, "Support du processeur");
                break;
                case 4:
                $produitsCompatibles += $this->checkCompatibility($request, $produitCtRepository, $produits, 2, "Support du processeur");
                break;
            }

            // Met à jour la liste des produits
            $produits = $produitsCompatibles;
        }

        // Crée la pagination pour la liste des produits
        $produitsPagination = $paginator->paginate(
            $produits, // Contenu à paginer
            $request->query->getInt('page', 1), // Page à afficher
            10 // Limite par page
        );

        $produits = $produitsPagination;

        // Calcul le prix total de la configuration
        $totalConfiguration = 0;
        if ($request->getSession()->get('configuration')) {
            foreach ($request->getSession()->get('configuration') as $produit) {
                $totalConfiguration += $produit->getprix();
            }
        }

        return $this->render('configurateur/index.html.twig', [
            'etape' => $etape,
            'titreEtape' => $titreEtape,
            'produits' => $produits,
            'configuration' => $request->getSession()->get('configuration'),
            'totalConfiguration' => $totalConfiguration,
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
        $configuration[$etape] = $produit;

        // Trie la configuration par ordre d'étapes
        ksort($configuration);

        // Stocke la configuration en session
        $request->getSession()->set('configuration', $configuration);

        return $this->redirectToRoute('configurateur', ['etape' => $etape + 1]);
    }

    // Retourne la liste des produits compatible avec ceux de l'étape spécifiée en comparant une caractéristique technique
    // spécifique du produit de la configuration de l'étape spécifiée à une caractéristique des produits de l'étape en cours
    public function checkCompatibility(Request $request, ProduitCaracteristiqueTechniqueRepository $produitCtRepository, $produits, int $etape, string $caracteristique) {
        // Récupère les caractèristiques techniques du produit à vérifier de la configuration
        $produitSource = $request->getSession()->get('configuration')[$etape];
        $produitSourceCt = $produitCtRepository->findBy(['produit' => $produitSource]);

        // Récupère les valeur des caractéristiques du produit à vérifier pour la caractéristique spécifiée
        $valeursCaracteristiquesTechniques = [];
        foreach($produitSourceCt as $produitCaracteristiqueTechnique) {
            if ($produitCaracteristiqueTechnique->getCaracteristiqueTechnique()->getNom() == $caracteristique) {
                $valeursCaracteristiquesTechniques[] = $produitCaracteristiqueTechnique->getValeur();
            }
        }

        // Crée la liste des produits compatibles
        $produitsCompatibles = [];
        foreach ($produits as $produit) { // Itère chaque produit
            foreach ($produit->getCaracteristiquesTechniques() as $produitCaracteristique) { // Itère chaque caractéristique technique de chaque produit
                if ($produitCaracteristique->getCaracteristiqueTechnique()->getNom() == $caracteristique) { // Si son nom est celui spécifié...
                    foreach ($valeursCaracteristiquesTechniques as $valeur) { // Itère les valeurs des caractéristiques compatibles...
                        if ($valeur == $produitCaracteristique->getValeur()) { // Vérifie que la valeur est compatible
                            $produitsCompatibles[] = $produit; // Ajoute le produit en tant que produit compatible
                        }
                    }
                }
            }
        }

        return $produitsCompatibles;
    }
}

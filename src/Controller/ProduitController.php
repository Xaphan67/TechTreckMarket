<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ProduitCommandeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    #[Route('/produit/{id}', name: 'voir_produit')]
    public function show(Produit $produit, AvisRepository $avisRepository, ProduitCommandeRepository $produitCommandeRepository, PaginatorInterface $paginator, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Génération des formulaires pour modifier la quantité et poster des avis
        $quantiteForm = $this->createForm(ProduitType::class);
        $avisForm = $this->createForm(AvisType::class);

        // Initialisation des variables
        $produitAchete = false;

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

        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Vérifie si le formulaire pour poster un avis est soumis
            $avisForm->handleRequest($request);
            if ($avisForm->isSubmitted()) {
                // Vérifie que le formulaire est valide
                if ($avisForm->isValid()) {
                    // Instancie un nouvel avis
                    $avi = new Avis();
    
                    // Récupère les informations du formulaire
                    $avi = $avisForm->getData();
    
                    // Ajoute l'utilisateur actif et le produit à l'avis
                    $avi->setUtilisateur($this->getUser());
                    $avi->setProduit($produit);
    
                    // Envoie en base de données
                    $entityManager->persist($avi);
                    $entityManager->flush();
                    
                    // Ajoute un message flash
                    $this->addFlash('success', 'Votre avis à bien été posté !');
    
                    // Vide puis génère un nouveau formulaire pour les avis
                    unset($avisForm);
                    $avisForm = $this->createForm(AvisType::class);
                } else {
                    // Ajoute un message flash
                    $this->addFlash('danger', 'Le formulaire n\'est pas valide !');
                }
            }

            // Vérifie que l'utilisateur connecté à acheté le produit
            $produitCommandes = $produitCommandeRepository->findBy(['produit' => $produit], ['id' => 'ASC']);
            foreach ($produitCommandes as $produitCommande) {
                if ($produitCommande->getCommande()->getUtilisateur() != null && $produitCommande->getCommande()->getUtilisateur()->getId() == $this->getUser()->getId()) {
                    $produitAchete = true;
                    break;
                }
            }
        }

        // Récupère les avis qui concernent le produit
        $avis = $avisRepository->findBy(['produit' => $produit], ['datePublication' => 'DESC']);

        // Crée la pagination pour la liste des avis
        $avisPagination = $paginator->paginate(
            $avis, // Contenu à paginer
            $request->query->getInt('page', 1), // Page à afficher
            10 // Limite par page
        );



        return $this->render('produit/show.html.twig', [
            'categoriesParent' => $categoriesParent,
            'avis' => $avisPagination,
            'produit' => $produit,
            'quantiteFormulaire' => $quantiteForm,
            'avisFormulaire' => $avisForm,
            'produitAchete' => $produitAchete
        ]);
    }
}

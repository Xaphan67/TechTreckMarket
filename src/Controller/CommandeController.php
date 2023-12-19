<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande/panier', name: 'afficher_panier')]
    public function showBarket(CommandeRepository $commandeRepository) : Response
    {
        // Vérifie qu'un utilisateur est connecté
        if($this->getUser())
        {
            // Récupère la commande active de l'utilisateur
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'utilisateur' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Récupère le prix total de la commande
            $total = 0;
            if ($commande)
            {
                foreach($commande->getProduitCommandes() as $produitCommande) {
                    $total += $produitCommande->getProduit()->getPrix() * $produitCommande->getQuantite();
                }
            }

            // Appel à la vue
            return $this->render('commande/showBarket.html.twig', [
                'commande' => $commande,
                'total' => $total
            ]);
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/commande/add/{id}', name: 'ajout_produit_commande')]
    public function addProduct(Produit $produit, CommandeRepository $commandeRepository, ProduitCommandeRepository $produitCommandeRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if($this->getUser())
        {
            // Récupère la commande active de l'utilisateur.
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'utilisateur' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Crée une nouvelle commande si l'utilisateur n'en à aucune à l'était "panier"
            if (!$commande) {
                $commande = new Commande($utilisateur);
                $utilisateur->addCommande($commande);
            }

            // Vérifie si le produit existe déjà dans la commande
            $produitActuel = $produitCommandeRepository->findOneBy([
                'commande' => $commande,
                'produit' => $produit
            ]);

            // Ajoute le produit à la commande s'il n'existe pas déjà
            if (!$produitActuel)
            {
                $commande->addProduitCommande(new ProduitCommande($commande, $produit));

                // Stocke la commande dans la base de données
                $entityManager->persist($commande);
                $entityManager->flush($commande);
            }
            else // Augmente la quantité s'il existe déjà
            {
                $produitActuel->setQuantite($produitActuel->getQuantite() + 1);
                $entityManager->persist($produitActuel);
                $entityManager->flush($produitActuel);
            }

            // Redirige vers le panier
            return $this->redirectToRoute('afficher_categorie', ['id' => $produit->getCategorie()->getId()]);
        }
        
        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/commande/delete/{id}', name:'supprimer_produit_commande')]
    public function removeProduct(Produit $produit, CommandeRepository $commandeRepository, ProduitCommandeRepository $produitCommandeRepository, EntityManagerInterface $entityManager)
    {
        // Vérifie qu'un utilisateur est connecté
        if($this->getUser())
        {
            // Récupère la commande active de l'utilisateur.
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'utilisateur' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Vérifie si le produit existe déjà dans la commande
            $produitExiste = $produitCommandeRepository->findOneBy([
                'commande' => $commande,
                'produit' => $produit
            ]);

            if ($produitExiste) // Supprime le produit s'il existe
            {
                $entityManager->remove($produitExiste);
                $entityManager->flush();
            }

            // Redirige vers le panier
            return $this->redirectToRoute('afficher_panier');
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/commande/validate/{id}', name:'valider_commande')]
    public function validateCommand(Commande $commande, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager)
    {
        // Vérifie qu'un utilisateur est connecté
        if($this->getUser())
        {
            // Récupère la commande active de l'utilisateur.
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'utilisateur' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Change l'état de la commande
            $commande->setEtat("en cours de préparation");
            $entityManager->persist($commande);
            $entityManager->flush($commande);
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

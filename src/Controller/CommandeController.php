<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande/panier', name: 'afficher_panier')]
    public function showBarket(CommandeRepository $commandeRepository, ProduitRepository $produitRepository, ProduitCommandeRepository $produitCommandeRepository) : Response
    {
        // Vérifie qu'un utilisateur est connecté
        if($this->getUser())
        {
            // Récupère la commande active de l'utilisateur
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'id' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Par défaut, la commande ne contient rien
            $lignesCommande = [];
            $total = 0;

            // Si la commande existe...
            if ($commande)
            {
                // Récupère les produits associés à cette commande
                $lignesCommande = $produitCommandeRepository->findBy(['commande' => $commande->getId()
                ]);

                // Récupère le prix total de la commande
                if ($lignesCommande)
                foreach ($lignesCommande as $ligneCommande)
                {
                    $prixLigne = $ligneCommande->getQuantite() * $ligneCommande->getProduit()->getPrix();
                    $total += $prixLigne;
                }     
            }   

            // Appel à la vue
            return $this->render('commande/showBarket.html.twig', [
                'commande' => $commande,
                'lignesCommande' => $lignesCommande,
                'total' => $total
            ]);
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/commande/add/{id}', name: 'ajout_produit_commande')]
    public function addProduct(Produit $produit, CommandeRepository $commandeRepository, ProduitCommandeRepository $produitCommandeRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if($this->getUser())
        {
            // Récupère la commande active de l'utilisateur.
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'id' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Crée une nouvelle commande si l'utilisateur n'en à aucune à l'était "panier"
            if (!$commande) {
                $commande = new Commande($utilisateur);
            }

            // Vérifie si le produit existe déjà dans la commande
            $produitExiste = $produitCommandeRepository->findOneBy([
                'commande' => $commande,
                'produit' => $produit
            ]);

            // Ajoute le produit à la commande s'il n'existe pas déjà
            if (!$produitExiste)
            {
                $produitsCommande = new ProduitCommande();
                $produitsCommande->setCommande($commande);
                $produitsCommande->setProduit($produit);
                $produitsCommande->setQuantite(1);
            }
            else // Augmente la quantité s'il existe déjà
            {
                $produitExiste->setQuantite($produitExiste->getQuantite() + 1);
                $produitsCommande = $produitExiste;
            }
            
            // Stocke la commande dans la base de données
            $entityManager->persist($commande);
            $entityManager->flush($commande);
            $entityManager->persist($produitsCommande);
            $entityManager->flush($produitsCommande);

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
                'id' => $utilisateur->getId(),
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
                'id' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Change l'état de la commande
            $commande->setEtat("préparation");
            $entityManager->persist($commande);
            $entityManager->flush($commande);
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}
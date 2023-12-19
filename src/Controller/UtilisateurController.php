<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InfosUtilisateurType;
use App\Repository\CommandeRepository;
use App\Form\MotDePasseUtilisateurType;
use App\Repository\ProduitCommandeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur/{id}', name: 'profil_utilisateur')]
    public function index(Utilisateur $utilisateur, CommandeRepository $commandeRepository, ProduitCommandeRepository $produitCommandeRepository): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if($this->getUser())
        {
            // Génération des formulaires pour modifier les informations personelles de l'utilisateur
            $infosForm = $this->createForm(InfosUtilisateurType::class, $utilisateur);
            $mdpForm = $this->createForm(MotDePasseUtilisateurType::class, $utilisateur);
           
            // Récupération des commandes de l'utilisateur
            $commandes = $utilisateur->getCommandes();

            $commandesEnCours = [];
            $commandesPassees = [];
            foreach($commandes as $commande)
            {
                if ($commande->getEtat() != "panier")
                {
                    if ($commande->getEtat() != "expédiée")
                    {
                        $total = 0;
                        foreach($commande->getProduitCommandes() as $produitCommande) {
                            $total += $produitCommande->getProduit()->getPrix() * $produitCommande->getQuantite();
                        }
                        $commandesEnCours[] = ['commande' => $commande, 'total' => $total];
                    }
                    else
                    {
                        $total = 0;
                        foreach($commande->getProduitCommandes() as $produitCommande) {
                            $total += $produitCommande->getProduit()->getPrix() * $produitCommande->getQuantite();
                        }
                        $commandesPassees[] = ['commande' => $commande, 'total' => $total];
                    }
                }
            }

            return $this->render('utilisateur/index.html.twig', [
                'utilisateur' => $utilisateur,
                'infosUtilisateur' => $infosForm,
                'mdpUtilisateur' => $mdpForm,
                'commandesEnCours' => $commandesEnCours,
                'commandesPassees' => $commandesPassees
            ]);
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

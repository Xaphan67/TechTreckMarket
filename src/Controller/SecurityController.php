<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\ProduitCommande;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('profil_utilisateur');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/login_confirm', name: 'app_login_confirm')]
    public function loginConfirm(CommandeRepository $commandeRepository, ProduitRepository $produitRepository, EntityManagerInterface $entityManager, Request $request) : Response
    {
        //Ajoute un message flash
        $this->addFlash("success", "Bienvenue " . (($this->getUser()->getPseudo() || ($this->getUser()->getNom() && $this->getUser()->getPrenom())) ? $this->getUser() : "") . " !");

        // S'il y a un panier en session, il est transféré à l'utilisateur qui se connecte
        // Récupère le panier en session
        $panier = $request->getSession()->get('panier');

        // Récupère la commande active de l'utilisateur.
        $utilisateur = $this->getUser();
        $commande = $commandeRepository->findOneBy([
            'utilisateur' => $utilisateur->getId(),
            'etat' => "panier"
        ]);

        // Crée une nouvelle commande si l'utilisateur n'en à aucune à l'état "panier"
        if (!$commande) {
            $commande = new Commande($utilisateur);
            $utilisateur->addCommande($commande);
        }

        // Supprime tout les produits de la commande
        foreach ($commande->getProduitCommandes() as $produitCommande) {
            $entityManager->remove($produitCommande);
            $entityManager->flush();
        }

        // Ajoute tous les produits du panier à la commande
        if ($panier) {
            foreach ($panier as $produitPanier) {
                if ($produitPanier != null) {
                    $prod = $produitRepository->findOneBy(['id' => $produitPanier['produit']->getId()]);
                    $commande->addProduitCommande(new ProduitCommande($prod, $produitPanier['quantite']));
                }
            }
        }

        // Stocke la commande dans la base de données
        $entityManager->persist($commande);
        $entityManager->flush($commande);
        
        // Supprime tout les produits du panier
        $request->getSession()->remove('panier');

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Form\ProduitType;
use App\Form\CommandeType;
use App\Entity\ProduitCommande;
use App\Entity\AdresseLivraison;
use App\Entity\AdresseFacturation;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitCommandeRepository;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommandeController extends AbstractController
{
    #[Route('/commande/panier', name: 'afficher_panier')]
    public function showBarket(CommandeRepository $commandeRepository, Request $request): Response
    {
        // initialise les variables
        $form = null;
        $commande = []; // Commande avec état panier stockée en BDD si l'utilisateur est connecté
        $panier = []; // Panier stocké en session si l'utilisateur n'est pas connecté

        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Récupère la commande active de l'utilisateur
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'utilisateur' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Récupère le prix total de la commande
            $total = 0;
            if ($commande) {
                foreach ($commande->getProduitCommandes() as $produitCommande) {
                    $total += $produitCommande->getProduit()->getPrix() * $produitCommande->getQuantite();
                }
            }

            // Génération du formulaire pour valider la commande
            $form = $this->createForm(CommandeType::class, $utilisateur);
        }
        else {
            // Récupère le panier en session
            $panier = $request->getSession()->get('panier');

            // Récupère le prix total du panier
            $total = 0;
            foreach ($panier as $produitPanier) {
                $total += $produitPanier['produit']->getPrix() * $produitPanier['quantite'];
            }
        }

        // Appel à la vue
        return $this->render('commande/showBarket.html.twig', [
            'commande' => $commande,
            'panier' => $panier,
            'total' => $total,
            'formulaire' => $form,
        ]);
    }

    #[Route('/commande/add/{id}/{recherche?}', name: 'ajout_produit_commande')]
    public function addProduct(Produit $produit, ?string $recherche, CommandeRepository $commandeRepository, ProduitCommandeRepository $produitCommandeRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
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
        } else {
            // Récupère le panier en session
            $panier = $request->getSession()->get('panier');
        }

        // Récupère les informations envoyés via le formulaire lors d'un ajout de produit via la fiche produit
        $form = $this->createForm(ProduitType::class);
        $form->handleRequest($request);

        // Enregistre l'url d'entrée dans une variable en session
        $request->getSession()->set('urlFrom', $request->headers->get('referer'));

        // Ajoute un produit, ou plus si le formulaire est soumis et est valide
        $quantite = 1;

        // Vérifie que le formulaire est soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupère les informations du formulaire
            $quantite = $form->getData()["quantite"];
        }

        // Si le formulaire et soumis et est valide, ou qu'on ajoute un produit sans passer par un formulaire
        if (($form->isSubmitted() && $form->isValid()) || !$form->isSubmitted()) {
            if ($this->getUser()) {
                // Ajoute le produit à la commande s'il n'existe pas déjà
                if (!$produitActuel) {
                    $commande->addProduitCommande(new ProduitCommande($produit, $quantite));

                    // Stocke la commande dans la base de données
                    $entityManager->persist($commande);
                    $entityManager->flush($commande);
                } else { // Augmente la quantité s'il existe déjà
                    $produitActuel->setQuantite($produitActuel->getQuantite() + $quantite);
                    $entityManager->persist($produitActuel);
                    $entityManager->flush($produitActuel);
                }
            } else {
                // Stoke le produit dans le panier en session
                $panier[$produit->getId()] = ['produit' => $produit, 'quantite' => $quantite];
                $request->getSession()->set('panier', $panier);
            }

            // Ajoute un message flash
            $this->addFlash('success', ($quantite > 1 ? $quantite . 'x ' : '') . $produit->getDesignation() .' ' . ($quantite > 1 ? 'ont' : 'à') . ' bien été' . ($quantite > 1 ? 's' : '') . ' ajouté' . ($quantite > 1 ? 's' : '') . ' au panier !');
        }

        // En cas de provenance de la page de recherche principale, redirige vers celle çi
        if ($recherche) {
            return $this->redirectToRoute('recherche_principale', ['recherche' => $recherche]);
        } else { // Sinon, redirige vers la fiche du produit, ou la catégorie du produit
            $url = $request->getSession()->get('urlFrom');
            $request->getSession()->remove('urlFrom');
            return $this->redirect($url);
        }
    }

    #[Route('commande/add_build', name: 'ajout_config_commande')]
    public function addBuildToBarket(ProduitRepository $produitRepository, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager, Request $request) {
        // Récupère la configuration stockée en session
        $configuration = $request->getSession()->get('configuration');

        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
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

            // Supprime tout les produits de la commande
            foreach ($commande->getProduitCommandes() as $produitCommande) {
                $entityManager->remove($produitCommande);
                $entityManager->flush();
            }

            // Ajoute tout les produits de la configuration à la commande
            foreach ($configuration as $produit) {
                if ($produit != null) {
                    $prod = $produitRepository->findOneBy(['id' => $produit->getId()]);
                    $commande->addProduitCommande(new ProduitCommande($prod, 1));
                }
            }

            // Stocke la commande dans la base de données
            $entityManager->persist($commande);
            $entityManager->flush($commande);

        } else {
            // Récupère le panier en session
            $panier = $request->getSession()->get('panier');

            // Supprime tout les produits du panier
            $request->getSession()->remove('panier');

            // Ajoute tout les produits de la configuration au panier
            foreach ($configuration as $produit) {
                $panier[$produit->getId()] = ['produit' => $produit, 'quantite' => 1];
            }
            $request->getSession()->set('panier', $panier);
        }

        // Ajoute un message flash
        $this->addFlash('success', 'La configuration à été ajoutée au panier !');

        // Redirige vers le panier
        return $this->redirectToRoute('afficher_panier');
    }

    #[Route('/commande/delete/{id}', name: 'supprimer_produit_commande')]
    public function removeProduct(Produit $produit, CommandeRepository $commandeRepository, ProduitCommandeRepository $produitCommandeRepository, EntityManagerInterface $entityManager, Request $request)
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
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

                // Ajoute un message flash
                $this->addFlash('success', 'Le produit à bien été supprimé du panier !');
            } else {
                // Ajoute un message flash
                $this->addFlash('danger', 'Le produit n\'a pas pu être supprimé du panier !');
            }
        } else {
            // Récupère le panier en session
            $panier = $request->getSession()->get('panier');

            // Supprime le produit
            unset($panier[$produit->getId()]);
            $request->getSession()->set('panier', $panier);

            // Ajoute un message flash
            $this->addFlash('success', 'Le produit à bien été supprimé du panier !');
        }

        // Redirige vers le panier
        return $this->redirectToRoute('afficher_panier');
    }

    #[Route('/commande/validate', name: 'valider_commande')]
    public function validateCommand(Request $request, CommandeRepository $commandeRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            $form = $this->createForm(CommandeType::class);
            $form->handleRequest($request);

            // Vérifie que le formulaire est soumis
            if ($form->isSubmitted()) {
                // Vérifie que le formulaire est valide
                if ($form->isValid()) {
                    // Récupère les informations du formulaire
                    $formData = $form->getData();

                    $adresseFacturation = $form->get('adresseFacturation')->getData();
                    if ($form->get('numeroFacturation')->getData() && $form->get('typeRueFacturation')->getData() && $form->get('rueFacturation')->getData() && $form->get('codePostalFacturation')->getData() && $form->get('villeFacturation')->getData()) {
                        $adresseFacturation = new AdresseFacturation();
                        $adresseFacturation->setUtilisateur($this->getUser());
                        $adresseFacturation->setNumero($form->get('numeroFacturation')->getData());
                        $adresseFacturation->setTypeRue($form->get('typeRueFacturation')->getData());
                        $adresseFacturation->setRue($form->get('rueFacturation')->getData());
                        $adresseFacturation->setCodePostal($form->get('codePostalFacturation')->getData());
                        $adresseFacturation->setVille($form->get('villeFacturation')->getData());
                        $adresseFacturation->setPreferee(0);
                    }

                    $adresseLivraison = $form->get('adresseLivraison')->getData();
                    if ($form->get('numeroLivraison')->getData() && $form->get('typeRueLivraison')->getData() && $form->get('rueLivraison')->getData() && $form->get('codePostalLivraison')->getData() && $form->get('villeLivraison')->getData()) {
                        $adresseLivraison = new AdresseLivraison();
                        $adresseLivraison->setUtilisateur($this->getUser());
                        $adresseLivraison->setNumero($form->get('numeroLivraison')->getData());
                        $adresseLivraison->setTypeRue($form->get('typeRueLivraison')->getData());
                        $adresseLivraison->setRue($form->get('rueLivraison')->getData());
                        $adresseLivraison->setCodePostal($form->get('codePostalLivraison')->getData());
                        $adresseLivraison->setVille($form->get('villeLivraison')->getData());
                        $adresseLivraison->setPreferee(0);
                    }

                    // Récupère la commande active de l'utilisateur.
                    $utilisateur = $this->getUser();
                    $commande = $commandeRepository->findOneBy([
                        'utilisateur' => $utilisateur->getId(),
                        'etat' => "panier"
                    ]);

                    // Récupère l'id et le prix des produits au moment de la commande et les stocke dans un JSON dans la commande
                    $prixProduits = [];
                    foreach ($commande->getProduitCommandes() as $produitCommande) {
                        $prixProduits[$produitCommande->getProduit()->getId()] = $produitCommande->getProduit()->getPrix();
                    }
                    $commande->setPrixProduits($prixProduits);

                    // Ajoute les informations personelles de l'utilisateur à la commande
                    $commande->setCivilite($formData->getCivilite());
                    $commande->setNom($formData->getNom());
                    $commande->setPrenom($formData->getPrenom());
                    $commande->setAdresseFacturation($adresseFacturation);
                    $commande->setAdresseLivraison($adresseLivraison);

                    // Change l'état de la commande
                    $commande->setEtat("en cours de préparation");

                    // Ajoute la commande en base de données
                    $entityManager->persist($commande);
                    $entityManager->flush($commande);

                    // Décrémente le stock de chaque produit dans la commande d'une unité
                    foreach ($commande->getProduitCommandes() as $produitCommande) {
                        $produit = $produitCommande->getProduit();
                        $produit->setStock($produit->getStock() - 1);
                        $entityManager->persist($produit);
                        $entityManager->flush($produit);
                    }

                    // Ajoute la nouvelle adresse de facturation et/ou de livraison si l'utilisateur l'a demandé
                    if ($form->get('enregistrerFacturation')->getData()) {
                        $entityManager->persist($adresseFacturation);
                        $entityManager->flush($adresseFacturation);
                    }
                    if ($form->get('enregistrerLivraison')->getData()) {
                        $entityManager->persist($adresseLivraison);
                        $entityManager->flush($adresseLivraison);
                    }

                    // Ajoute un message flash
                    $this->addFlash('success', 'Votre commande à bien été enregistrée !');

                    // Envoie un mail de confirmation
                    return $this->redirectToRoute('email_commande', ['id' => $commande->getId()]);
                } else {
                    // Ajoute un message flash
                    $this->addFlash('danger', 'Le formulaire n\'est pas valide !');
                }
            }
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

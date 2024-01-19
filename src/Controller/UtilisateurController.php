<?php

namespace App\Controller;

use App\Form\AdresseLivraisonType;
use App\Form\InfosUtilisateurType;
use App\Form\AdresseFacturationType;
use App\Form\MotDePasseUtilisateurType;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur', name: 'profil_utilisateur')]
    public function index(CommandeRepository $commandeRepository): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Récupère l'utilisateur actuellement connecté
            $utilisateur = $this->getUser();

            // Génération des formulaires pour modifier les informations personelles de l'utilisateur
            $infosForm = $this->createForm(InfosUtilisateurType::class, $utilisateur);
            $mdpForm = $this->createForm(MotDePasseUtilisateurType::class, $utilisateur);
            $adresseFacturationForm = $this->createForm(AdresseFacturationType::class);
            $adresseLivraisonForm = $this->createForm(AdresseLivraisonType::class);

            // Récupération des commandes de l'utilisateur
            $commandes = $commandeRepository->findBy(['utilisateur' => $utilisateur], ["id" => "DESC"]);

            $commandesEnCours = [];
            $commandesPassees = [];
            foreach ($commandes as $commande) {
                if ($commande->getEtat() != "panier") {
                    if ($commande->getEtat() != "expédiée") {
                        $total = 0;
                        foreach ($commande->getProduitCommandes() as $produitCommande) {
                            $total += $commande->getPrixProduits()[$produitCommande->getProduit()->getId()] * $produitCommande->getQuantite();
                        }
                        $commandesEnCours[] = ['commande' => $commande, 'total' => $total];
                    } else {
                        $total = 0;
                        foreach ($commande->getProduitCommandes() as $produitCommande) {
                            $total += $commande->getPrixProduits()[$produitCommande->getProduit()->getId()] * $produitCommande->getQuantite();
                        }
                        $commandesPassees[] = ['commande' => $commande, 'total' => $total];
                    }
                }
            }

            // Récupération des adresses de l'utilisateur
            $adressesFacturation = $utilisateur->getAdressesFacturation();
            $adressesLivraison = $utilisateur->getAdressesLivraison();

            // Appel à la vue
            return $this->render('utilisateur/index.html.twig', [
                'infosUtilisateur' => $infosForm,
                'mdpUtilisateur' => $mdpForm,
                'adresseFacturationFormulaire' => $adresseFacturationForm,
                'adresseLivraisonFormulaire' => $adresseLivraisonForm,
                'commandesEnCours' => $commandesEnCours,
                'commandesPassees' => $commandesPassees,
                'adressesFacturation' => $adressesFacturation,
                'adressesLivraison' => $adressesLivraison
            ]);
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('utilisateur/modifier', name:'modifier_donnees_utilisateur')]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Récupère l'utilisateur actuellement connecté
            $utilisateur = $this->getUser();

            // Génère et récupère les informations envoyés via le formulaire
            $form = $this->createForm(InfosUtilisateurType::class, $utilisateur);
            $form->handleRequest($request);

            // Vérifie que le formulaire est soumis
            if ($form->isSubmitted()) {
                //Vérifie que le formulaire est valide
                if ($form->isValid()) {
                    // Récupère les informations du formulaire
                    $formData = $form->getData();

                    // Met à jour l'utilisateur en base de donnée
                    $entityManager->persist($formData);
                    $entityManager->flush();

                    // Ajoute un message flash
                    $this->addFlash('success', 'Informations personelles mises à jour !');
                } else {
                    // Ajoute un message flash
                    $this->addFlash('danger', 'Le formulaire n\'est pas valide !');
                }
            }
        }

        // Redirige vers le profil de l'utilisateur
        return $this->redirectToRoute('profil_utilisateur');
    }

    #[Route('utilisateur/modifierMdp', name:'modifier_mdp_utilisateur')]
    public function editMdp(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Récupère l'utilisateur actuellement connecté
            $utilisateur = $this->getUser();

            // Génère et récupère les informations envoyés via le formulaire
            $form = $this->createForm(MotDePasseUtilisateurType::class, $utilisateur);
            $form->handleRequest($request);

            // Vérifie que le formulaire est soumis
            if ($form->isSubmitted()) {
                //Vérifie que le formulaire est valide
                if ($form->isValid()) {
                    // Récupère les informations du formulaire
                    $ancienMdp = $form->get('oldPassword')->getData();
                    $nouveauMdp = $form->get('password')->getData();

                    // Vérifie si le mot de passe saisi correspond au mot de passe actuel de l'utilisateur
                    if ($passwordHasher->isPasswordValid($utilisateur, $ancienMdp)) {
                        // Hashe le nouveau mot de passe et modifie le mot de passe actuel de l'utilisateur
                        $nouveauMdpHash = $passwordHasher->hashPassword($utilisateur, $nouveauMdp);
                        $utilisateur->setPassword($nouveauMdpHash);

                        // Met à jour l'utilisateur en base de donnée
                        $entityManager->persist($utilisateur);
                        $entityManager->flush();

                        // Ajoute un message flash
                        $this->addFlash('success','Mot de passe mis à jour !');
                    } else {
                        // Ajoute un message flash
                        $this->addFlash('danger', 'Le mot de passe actuel est incorrect !');
                    }
                } else {
                    // Ajoute un message flash
                    $this->addFlash('danger', 'Le formulaire n\'est pas valide !');
                }
            }
        }

        // Redirige vers le profil de l'utilisateur
        return $this->redirectToRoute('profil_utilisateur');
    }

    #[Route('utilisateur/supprimerCompte', name:'supprimer_compte_utilisateur')]
    public function delete(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage)
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {

            // Supprime l'utilisateur de la base de données
            $entityManager->remove($this->getUser());
            $entityManager->flush();

            // Déconnecte l'utilisateur
            $tokenStorage->setToken(null);

            // Ajoute un message flash
            $this->addFlash('success', 'Votre compte à bien été supprimé !');
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

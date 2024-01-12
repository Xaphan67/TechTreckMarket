<?php

namespace App\Controller;

use App\Form\AdresseLivraisonType;
use App\Entity\AdresseLivraison;
use App\Repository\AdresseLivraisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseLivraisonController extends AbstractController
{
    #[Route('/adresseLivraison/ajout', name: 'ajout_adresse_livraison')]
    #[Route('/adresseLivraison/modifier/{id}', name:'modifier_adresse_livraison')]
    public function new_edit(AdresseLivraison $adresseLivraison = null, Request $request, AdresseLivraisonRepository $adresseLivraisonRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {

            // Instancie une nouvelle adresse de livraison en cas d'ajout
            $ajoutAdresse = false;
            if (!$adresseLivraison) {
                $adresseLivraison = new AdresseLivraison();
                $ajoutAdresse = true;
            } else { // Securité CSRF en cas d'édition
                $token = $request->request->get('token');
            }

            // Instancie un formulaire de type AdresseLivraison
            $form = $this->createForm(AdresseLivraisonType::class, $adresseLivraison);
            $form->handleRequest($request);

            // Vérifie que le formulaire est soumis et est valide
            if ($form->isSubmitted()) {
                if (($ajoutAdresse && $form->isValid()) || (!$ajoutAdresse && $this->isCsrfTokenValid('update-adresse-livraison', $token))) {
                    // Récupère les informations du formulaire
                    $adresseLivraison = $form->getData();

                    // En cas d'ajout, rattache l'utilisateur à l'adresse
                    if ($ajoutAdresse) {
                        $adresseLivraison->setUtilisateur($this->getUser());
                    }

                    // Envoie en base de données
                    $entityManager->persist($adresseLivraison);
                    $entityManager->flush();

                    // Marque les autres adresses comme non préférées si celle-çi est marquée comme préférée
                    if ($adresseLivraison->isPreferee()) {
                        $adresseLivraisonRepository->setOthersAsNotFavorite($this->getUser(), $adresseLivraison);
                    }

                    // Ajoute un message flash
                    $this->addFlash('success', 'Adresse de livraison ' . ($ajoutAdresse ? 'enregistrée' : 'mise à jour') . ' !');

                    return $this->redirectToRoute('profil_utilisateur');
                } else {
                    // Ajoute un message flash
                    $this->addFlash('danger', 'Le formulaire n\'est pas valide !');
                }
            }
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/adresseLivraison/supprimer/{id}', name: 'supprimer_adresse_livraison')]
    public function delete(AdresseLivraison $adresse, EntityManagerInterface $entityManager) : Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Vérifie que l'adresse appartient à l'utilisateur connecté
            if ($adresse->getUtilisateur() == $this->getUser()) {
                $entityManager->remove($adresse);
                $entityManager->flush();

                // Ajoute un message flash
                $this->addFlash('success', 'Adresse de livraison supprimée !');

                return $this->redirectToRoute('profil_utilisateur');
            } else {
                // Ajoute un message flash
                $this->addFlash('danger', 'Cette action n\'est pas autorisée !');
            }
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

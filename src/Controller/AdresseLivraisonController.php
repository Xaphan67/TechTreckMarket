<?php

namespace App\Controller;

use App\Form\AdresseType;
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
            $form = $this->createForm(AdresseType::class);
            $form->handleRequest($request);

            // Securité CSRF en cas d'édition
            if ($adresseLivraison) {
                $token = $request->request->get('token');
            }

            // Vérifie que le formulaire est soumis et est valide
            if ($form->isSubmitted() && ((!$adresseLivraison && $form->isValid()) || ($adresseLivraison && $this->isCsrfTokenValid('update-adresse-livraison', $token)) )) {
                // Récupère les informations du formulaire
                $formData = $form->getData();
                $formData["utilisateur"] = $this->getUser();

                // Crée une nouvelle adresse de livraison, ou modifie celle existante
                if (!$adresseLivraison) {
                    $adresseLivraison = new AdresseLivraison($formData);
                } else {
                    $adresseLivraison->setNumero($formData["numero"]);
                    $adresseLivraison->setTypeRue($formData["typeRue"]);
                    $adresseLivraison->setRue($formData["rue"]);
                    $adresseLivraison->setCodePostal($formData["codePostal"]);
                    $adresseLivraison->setVille($formData["ville"]);
                    $adresseLivraison->setPreferee($formData["preferee"]);
                }

                // Envoie en base de données
                $entityManager->persist($adresseLivraison);
                $entityManager->flush();

                // Marque les autres adresses comme non préférées si celle-çi est marquée comme préférée
                if ($adresseLivraison->isPreferee()) {
                    $adresseLivraisonRepository->setOthersAsNotFavorite($adresseLivraison);
                }

                return $this->redirectToRoute('profil_utilisateur');
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

                return $this->redirectToRoute('profil_utilisateur');
            }
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

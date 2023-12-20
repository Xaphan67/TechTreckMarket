<?php

namespace App\Controller;

use App\Form\AdresseType;
use App\Entity\AdresseLivraison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseLivraisonController extends AbstractController
{
    #[Route('/adresseLivraison/ajout', name: 'ajout_adresse_livraison')]
    public function new_edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            $form = $this->createForm(AdresseType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $adresse = $form->getData();
                $adresse["utilisateur"] = $this->getUser();

                $adresseLivraison = new AdresseLivraison($adresse);

                $entityManager->persist($adresseLivraison);
                $entityManager->flush();

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

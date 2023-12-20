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
    #[Route('/adresseLivraison/modifier/{id}')]
    public function new_edit(AdresseLivraison $adresseLivraison = null, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            $form = $this->createForm(AdresseType::class);
            $form->handleRequest($request);

            // Securité CSRF en cas d'édition
            if ($adresseLivraison) {
                $token = $request->request->get('token');
            }

            if ($form->isSubmitted() && ((!$adresseLivraison && $form->isValid()) || ($adresseLivraison && $this->isCsrfTokenValid('update-adresse-livraison', $token)) )) {
                $formData = $form->getData();
                $formData["utilisateur"] = $this->getUser();

                if (!$adresseLivraison) {
                    $adresseLivraison = new AdresseLivraison($formData);
                } else {
                    $adresseLivraison->setNumero($formData["numero"]);
                    $adresseLivraison->setTypeRue($formData["typeRue"]);
                    $adresseLivraison->setRue($formData["rue"]);
                    $adresseLivraison->setCodePostal($formData["codePostal"]);
                    $adresseLivraison->setVille($formData["ville"]);
                }

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

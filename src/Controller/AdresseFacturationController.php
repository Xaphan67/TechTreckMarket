<?php

namespace App\Controller;

use App\Form\AdresseFacturationType;
use App\Entity\AdresseFacturation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AdresseFacturationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseFacturationController extends AbstractController
{
    #[Route('/adresseFacturation/ajout', name: 'ajout_adresse_facturation')]
    #[Route('/adresseFacturation/modifier/{id}', name:'modifier_adresse_facturation')]
    public function new_edit(AdresseFacturation $adresseFacturation = null, Request $request, AdresseFacturationRepository $adresseFacturationonRepository, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {

            // Instancie une nouvelle adresse de facturation en cas d'ajout
            $ajoutAdresse = false;
            if (!$adresseFacturation) {
                $adresseFacturation = new AdresseFacturation();
                $ajoutAdresse = true;
            } else { // Securité CSRF en cas d'édition
                $token = $request->request->get('token');
            }

            // Instancie un formulaire de type AdresseFacturation
            $form = $this->createForm(AdresseFacturationType::class, $adresseFacturation);
            $form->handleRequest($request);

            // Vérifie que le formulaire est soumis et est valide
            if ($form->isSubmitted() && (($ajoutAdresse && $form->isValid()) || (!$ajoutAdresse && $this->isCsrfTokenValid('update-adresse-facturation', $token)) )) {
                // Récupère les informations du formulaire
                $adresseFacturation = $form->getData();

                // En cas d'ajout, rattache l'utilisateur à l'adresse
                if ($ajoutAdresse) {
                    $adresseFacturation->setUtilisateur($this->getUser());
                }

                // Envoie en base de données
                $entityManager->persist($adresseFacturation);
                $entityManager->flush();

                // Marque les autres adresses comme non préférées si celle-çi est marquée comme préférée
                if ($adresseFacturation->isPreferee()) {
                    $adresseFacturationonRepository->setOthersAsNotFavorite($adresseFacturation);
                }

                return $this->redirectToRoute('profil_utilisateur');
            }
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/adresseFacturation/supprimer/{id}', name: 'supprimer_adresse_facturation')]
    public function delete(AdresseFacturation $adresse, EntityManagerInterface $entityManager) : Response
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

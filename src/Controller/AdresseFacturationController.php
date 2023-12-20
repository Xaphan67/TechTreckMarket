<?php

namespace App\Controller;

use App\Form\AdresseType;
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
            $form = $this->createForm(AdresseType::class);
            $form->handleRequest($request);

            // Securité CSRF en cas d'édition
            if ($adresseFacturation) {
                $token = $request->request->get('token');
            }

            // Vérifie que le formulaire est soumis et est valide
            if ($form->isSubmitted() && ((!$adresseFacturation && $form->isValid()) || ($adresseFacturation && $this->isCsrfTokenValid('update-adresse-facturation', $token)) )) {
                // Récupère les informations du formulaire
                $formData = $form->getData();
                $formData["utilisateur"] = $this->getUser();

                // Crée une nouvelle adresse de facturration, ou modifie celle existante
                if (!$adresseFacturation) {
                    $adresseFacturation = new AdresseFacturation($formData);
                } else {
                    $adresseFacturation->setNumero($formData["numero"]);
                    $adresseFacturation->setTypeRue($formData["typeRue"]);
                    $adresseFacturation->setRue($formData["rue"]);
                    $adresseFacturation->setCodePostal($formData["codePostal"]);
                    $adresseFacturation->setVille($formData["ville"]);
                    $adresseFacturation->setPreferee($formData["preferee"]);
                }

                // Envoie en base de données
                $entityManager->persist($adresseFacturation);
                $entityManager->flush();

                // Marque les autres adresses comme non préférées si celle-çi est marquée comme préférée
                if ($adresseFacturation->isPreferee()) {
                    $adresseFacturationonRepository->setAllOtherAdressesAsNotFavorite($adresseFacturation);
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

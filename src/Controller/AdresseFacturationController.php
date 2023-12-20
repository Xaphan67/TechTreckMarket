<?php

namespace App\Controller;

use App\Entity\AdresseFacturation;
use App\Form\AdresseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdresseFacturationController extends AbstractController
{
    #[Route('/adresseFacturation/ajout', name: 'ajout_adresse_facturation')]
    public function new_edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            $form = $this->createForm(AdresseType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $adresse = $form->getData();
                $adresse["utilisateur"] = $this->getUser();

                $adresseFacturation = new AdresseFacturation($adresse);

                $entityManager->persist($adresseFacturation);
                $entityManager->flush();

                return $this->redirectToRoute('profil_utilisateur');
            }
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

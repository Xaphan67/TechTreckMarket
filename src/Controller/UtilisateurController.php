<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InfosUtilisateurType;
use App\Form\MotDePasseUtilisateurType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UtilisateurController extends AbstractController
{
    #[Route('/utilisateur/{id}', name: 'profil_utilisateur')]
    public function index(Utilisateur $utilisateur): Response
    {
        $infosForm = $this->createForm(InfosUtilisateurType::class, $utilisateur);
        $mdpForm = $this->createForm(MotDePasseUtilisateurType::class, $utilisateur);

        return $this->render('utilisateur/index.html.twig', [
            'utilisateur' => $utilisateur,
            'infosUtilisateur' => $infosForm,
            'mdpUtilisateur' => $mdpForm
        ]);
    }
}

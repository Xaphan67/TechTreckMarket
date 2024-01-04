<?php

namespace App\Controller;

use App\Entity\Commande;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
    #[Route('/emailCommande/{id}', name: 'email_commande')]
    public function sendOrder(Commande $commande, MailerInterface $mailer): Response
    {
        // Crée un nouvel email
        $email = (new TemplatedEmail())
            ->from('admin@ecommerce.com')
            ->to($this->getUser()->getEmail())
            ->subject('Merci pour votre commande')
            ->htmlTemplate('email/sendOrder.html.twig')
            ->context([
                'commande' => $commande
            ]);
            
        // Envoie l'email
        $mailer->send($email);

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

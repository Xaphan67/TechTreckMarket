<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Commande;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PDFController extends AbstractController
{
    #[Route('/pdf/genererFacture/{id}', name: 'generer_facture_pdf')]
    public function generatePDF(Commande $commande): Response
    {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Instancie Dompdf
            $dompdf = new Dompdf();

            // Configure les options de Dompdf
            $options = $dompdf->getOptions();
            $options->setDefaultFont('Courier');
            $options->setIsRemoteEnabled(true);
            $dompdf->setOptions($options);

            // Récupère les données qui seront à afficher
            $data = [
                'numeroFacture' => $commande->getId(),
                'numeroClient' => $commande->getUtilisateur()->getId(),
                'civilite' => $commande->getCivilite() == "homme" ? "Monsieur" : "Madame",
                'prenom' => $commande->getPrenom(),
                'nom' => $commande->getNom(),
                'dateCommande' => $commande->getDateCommande(),
                'adresseFacturation' => $commande->getAdresseFacturation(),
                'adresseLivraison' => $commande->getAdresseLivraison()
            ];

            $total = 0;
            $totalHT = 0;
            foreach($commande->getProduitCommandes() as $produit) {
                $data["produits"][$produit->getProduit()->getId()] = [
                    "designation" => $produit->getProduit()->getDesignation(),
                    "quantite" => $produit->getQuantite(),
                    "prix" => $commande->getPrixProduits()[$produit->getProduit()->getId()],
                    "prixHT" => round($commande->getPrixProduits()[$produit->getProduit()->getId()] / ( 1.2), 2)
                ];
                $total += $commande->getPrixProduits()[$produit->getProduit()->getId()] * $produit->getQuantite();
                $totalHT += round($commande->getPrixProduits()[$produit->getProduit()->getId()] / ( 1.2), 2) * $produit->getQuantite();
            }

            $data["total"] = $total;
            $data["totalHT"] = $totalHT;

            // Récupère le code HTML généré dans la vue twig
            $html =  $this->renderView('pdf/generateOrderPDF.html.twig', $data);

            // Envoie le code HTML à Dompdf
            $dompdf->loadHtml($html);


            // Affiche le code HTML en tant que PDF
            $dompdf->render();

            // Affiche le PDF dans le navigateur
            return new Response (
                $dompdf->stream('resume', ["Attachment" => false]),
                Response::HTTP_OK,
                ['Content-Type' => 'application/pdf']
            );
        }

        // Redirige vers la page d'accueil
        return $this->redirectToRoute('app_accueil');
    }
}

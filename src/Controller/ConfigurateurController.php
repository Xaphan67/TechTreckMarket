<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\ProduitConfig;
use App\Entity\ConfigurationPC;
use App\Form\ConfigurationPCType;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitConfigRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ConfigurationPCRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProduitCaracteristiqueTechniqueRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

const MAX_ETAPES = 8;

class ConfigurateurController extends AbstractController
{
    #[Route('/configurateur/creer', name: 'configurateur')]
    public function index(CategorieRepository $categorieRepository, ProduitRepository $produitRepository, ProduitCaracteristiqueTechniqueRepository $produitCtRepository, ConfigurationPCRepository $configurationPCRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Initialisation des variables
        $nomCategorie = null;
        $titreEtape = null;
        $produits = [];
        $configurations = [];
        $configurationsPrixTotal = [];

        // Récupère l'étape la plus avancée de la configuration stockée en sesison
        $etape = 1;
        $configuration = $request->getSession()->get('configuration');

        // Vérifie si il y à au moins un produit dans la configuration
        $produitsConfiguration = [];
        if ($configuration) {
            foreach ($configuration as $etape => $produitConfiguration) {
                if ($produitConfiguration != null) {
                    $produitsConfiguration[$etape] = $produitConfiguration;
                }
            }
        }

        if ($configuration) {
            foreach ($configuration as $etapeProduit => $produit) {
                $etape = $etapeProduit;
            }

            // Affiche l'étape suivante
            $etape += 1;
        }

        // Redirige vers le récapitulatif si toutes les étapes sont complétées
        if ($etape > MAX_ETAPES) {
            return $this->redirectToRoute('recapitulatif_configuration');
        }

        // Séléctionne la catégorie des produits qui seront proposés en fonction de l'étape actuelle
        // Etape => [Nom catégorie => Titre étape]
        $infosEtapes = [
            1 => ["Boîtiers" => "boîtier"],
            2 => ["Cartes mère" => "carte mère"],
            3 => ["Processeurs" => "processeur"],
            4 => ["Refroidissement" => "ventirad"],
            5 => ["Mémoire" => "mémoire"],
            6 => ["Cartes graphique" => "carte graphique"],
            7 => ["Stockage" => "stockage"],
            8 => ["Alimentations" => "alimentation"],
        ];

        // Récupère la catégorie correspondante à l'étape
        $categorie = $categorieRepository->findOneBy(['nom' => key($infosEtapes[$etape])]);

        // Vérifie s'il y a des sous-catégories
        if (count($categorie->getSousCategories()) > 0) {
            // Si oui, ajoute tout les produit de chaque sous-catégorie
            foreach ($categorie->getSousCategories() as $sousCategorie) {
                $produits += $produitRepository->findBy(['categorie' => $sousCategorie]);
            }
        } else {
            // Récupère les produits correspondant à la catégorie
            $produits = $produitRepository->findBy(['categorie' => $categorie]);
        }

        // Vérifications à chaque étape
        // Tableau clé => valeur
        // clé = numéro de l'étape à vérifier
        // valeur = tableau de comparaison : caractéristique sur le produit déja existant sur la config => caractéristique du produit à ajouter
        $caracteristiquesAVerifier = [];
        switch ($etape) {
            case 2:
                $caracteristiquesAVerifier = [
                    1 => [
                        "Format de carte mère" => "Format de carte mère"
                    ]
                ];
                break;
            case 3:
                $caracteristiquesAVerifier = [
                    2 => [
                        "Support du processeur" => "Support du processeur"
                    ]
                ];
                break;
            case 4:
                $caracteristiquesAVerifier = [
                    2 => [
                        "Support du processeur" => "Support du processeur"
                    ]
                ];
                break;
            case 5:
                $caracteristiquesAVerifier = [
                    2 => [
                        "Fréquence(s) Mémoire" => "Fréquence(s) Mémoire",
                        "Nombre de slots mémoire" => "Nombre de barrettes"
                    ]
                ];
                break;
            case 6:
                $caracteristiquesAVerifier = [
                    1 => [
                        "Longueur max. carte graphique" => "Longueur"
                    ],
                    2 => [
                        "Type de connecteur(s) graphique" => "Bus"
                    ]
                ];
                break;
            case 7:
                $caracteristiquesAVerifier = [
                    2 => [
                        "Connecteurs pour disques durs" => "Interface avec l'ordinateur"
                    ]
                ];
                break;
            case 8:
                $caracteristiquesAVerifier = [
                    6 => [
                        "Connecteur alimentation" => "Connecteurs"
                    ],
                    7 => [
                        "Interface avec l'ordinateur" => "Connecteurs"
                    ]
                ];
                break;
        }

        // Met à jour la liste des produits
        $produits = $this->checkCompatibility($request, $produitCtRepository, $produits, $caracteristiquesAVerifier);

        // Crée la pagination pour la liste des produits
        $produitsPagination = $paginator->paginate(
            $produits, // Contenu à paginer
            $request->query->getInt('page', 1), // Page à afficher
            10 // Limite par page
        );

        $produits = $produitsPagination;

        // Calcul le prix total de la configuration
        $totalConfiguration = 0;
        if ($produitsConfiguration) {
            foreach ($produitsConfiguration as $produit) {
                if ($produit != null) {
                    $totalConfiguration += $produit->getprix();
                }
            }
        }

        // Instancie un formulaire de type ConfigurationPCType
        $form = $this->createForm(ConfigurationPCType::class, null);

        // Récupère les configurations enregistrés pour l'utilisateur
        if ($this->getUser()) {
            $configurations = $configurationPCRepository->findBy(['utilisateur' => $this->getUser()], ['nom' => 'ASC']);

            // Rédcupère le prix de chaque configuration
            foreach ($configurations as $configuration) {
                $totalConfig = 0;
                foreach ($configuration->getProduitConfigs() as $produitConfig) {
                    $totalConfig += $produitConfig->getProduit()->getPrix();
                }
                $configurationsPrixTotal[$configuration->getId()] = $totalConfig;
            }
        }

        return $this->render('configurateur/index.html.twig', [
            'etape' => $etape,
            'infosEtapes' => $infosEtapes,
            'produits' => $produits,
            'configuration' => $produitsConfiguration,
            'totalConfiguration' => $totalConfiguration,
            'formulaire' => $form,
            'configurations' => $configurations,
            'configurationsPrixTotal' => $configurationsPrixTotal
        ]);
    }

    #[Route('/configurateur/ajout/{id}/{etape}', name: 'ajout_configurateur')]
    public function add(Produit $produit, int $etape, Request $request)
    {
        // Initialise les vraiables
        $configuration = [];

        // Vérifie si l'utilisateur à déja une configuration en session
        if ($request->getSession()->get('configuration')) {
            // Récupère la configuration stockée en session
            $configuration = $request->getSession()->get('configuration');
        }

        // Ajoute le produit à la configuration
        $configuration[$etape] = $produit;

        // Trie la configuration par ordre d'étapes
        ksort($configuration);

        // Stocke la configuration en session
        $request->getSession()->set('configuration', $configuration);

        // Redirige vers l'étape suivante, ou vers le résumé de la configuration
        if ($etape != MAX_ETAPES) {
            return $this->redirectToRoute('configurateur');
        } else {
            return $this->redirectToRoute('recapitulatif_configuration');
        }
    }

    #[Route('/configurateur/supprimer/{etape}', name: 'supprimer_configurateur')]
    public function remove(int $etape, Request $request)
    {
        // Récupère la configuration stockée en session
        $configuration = $request->getSession()->get('configuration');

        // Supprime le produit de la configuration
        $configuration[$etape] = null;

        // Stocke la configuration en session
        $request->getSession()->set('configuration', $configuration);

        // Redirige vers l'étape en cours
        return $this->redirectToRoute('configurateur');
    }

    #[Route('/configurateur/passer/{etape}', name: 'passer_configurateur')]
    public function skip(int $etape, Request $request)
    {
        // Initialise les vraiables
        $configuration = [];

        // Vérifie si l'utilisateur à déja une configuration en session
        if ($request->getSession()->get('configuration')) {
            // Récupère la configuration stockée en session
            $configuration = $request->getSession()->get('configuration');
        }

        // Ajoute une valeur null à la configuration à l'étape correspondante
        $configuration[$etape] = null;

        // Trie la configuration par ordre d'étapes
        ksort($configuration);

        // Stocke la configuration en session
        $request->getSession()->set('configuration', $configuration);

        // Redirige vers l'étape suivante
        return $this->redirectToRoute('configurateur');
    }

    #[Route('/configurateur/terminer', name: 'terminer_configurateur')]
    public function finish(Request $request)
    {
        // Récupère la configuration stockée en session
        $configurationSession = $request->getSession()->get('configuration');

        // Si aucune configuration, créer une configuration vide;
        if (!$configurationSession) {
            $configurationSession = [];
        }

        // Ajoute des produits vides pour les étapes non effectuées
        for ($i = 1; $i <= MAX_ETAPES; $i++) {
            if (!array_key_exists($i, $configurationSession)) {
                $configurationSession[$i] = null;
            }
        }

        // Enregistre la configuration en session
        $request->getSession()->set('configuration', $configurationSession);

        // Redirige vers le récapitulatif
        return $this->redirectToRoute('recapitulatif_configuration');
    }

    #[Route('/configurateur/sauvegarder/', name: 'sauvegarder_configuration')]
    public function save(ProduitRepository $produitRepository, ConfigurationPCRepository $configurationPCRepository, ProduitConfigRepository $produitConfigRepository, EntityManagerInterface $entityManager, Request $request) {
        // Enregistre l'url d'entrée dans une variable en session
        $request->getSession()->set('urlFrom', $request->headers->get('referer'));

        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Vérifie le nombre de configurations déja enregistrées
            $configurations = $configurationPCRepository->findBy(['utilisateur' => $this->getUser()]);

            if (count($configurations) < 6) {
                // Récupère la configuration stockée en session
                $configurationSession = $request->getSession()->get('configuration');

                if ($configurationSession) {
                    // Récupère le formulaire
                    $form = $this->createForm(ConfigurationPCType::class);
                    $form->handleRequest($request);

                    // Vérifie que le formulaire est soumis
                    if ($form->isSubmitted()) {
                        // Vérifie que le formulaire est valide
                        if ($form->isValid()) {
                            // Récupère le nom de la confiration à partir du formulaire
                            $nomConfiguration = $form->getData()['nom'];

                            // Récupère la configuration de l'utilisateur ayant le même nom, si elle existe
                            $configuration = $configurationPCRepository->findOneBy(['nom' => $nomConfiguration]);

                            // Crée une nouvelle configuration si l'utilisateur n'a aucune configuration enregistrée avec ce nom
                            if (!$configuration) {
                                $configuration = new ConfigurationPC($nomConfiguration);
                                $this->getUser()->addConfiguration($configuration);
                            }

                            // Récupère et supprime les produits de la configuration correspondante de l'utilisateur
                            $produitsConfiguration = $produitConfigRepository->findBy(['configurationPC' => $configuration]);

                            foreach ($produitsConfiguration as $produitConfiguration) {
                                $configuration->removeProduitsConfig($produitConfiguration);
                            }

                            // Ajoute les nouveaux produits
                            foreach ($configurationSession as $etape => $nouveauProduit) {
                                if ($nouveauProduit != null) {
                                    $prod = $produitRepository->findOneBy(['id' => $nouveauProduit->getId()]);
                                    $configuration->addProduitConfig(new ProduitConfig($prod, 1, $etape));
                                }
                            }

                            // Stocke la configuration dans la base de données
                            $entityManager->persist($configuration);
                            $entityManager->flush($configuration);

                            // Ajoute un message flash
                            $this->addFlash('success', 'Votre configuration à bien été sauvegardée !');
                        } else {
                            // Ajoute un message flash
                            $this->addFlash('danger', 'Le formulaire n\'est pas valide !');
                        }

                        // Redirige vers l'url d'entrée
                        $url = $request->getSession()->get('urlFrom');
                        $request->getSession()->remove('urlFrom');
                        return $this->redirect($url);
                    }
                } else {
                    // Ajoute un message flash
                    $this->addFlash('danger', 'Votre configuration est vide !');
                }
            } else {
                // Ajoute un message flash
                $this->addFlash('danger', 'Vous ne pouvez pas enregistrer plus de 6 configurations !');
            }
            
        } else {
            // Ajoute un message flash
            $this->addFlash('danger', 'Vous devez être connecté pour sauvegarder une configuration !');
        }

        // Redirige vers l'url d'entrée
        $url = $request->getSession()->get('urlFrom');
        $request->getSession()->remove('urlFrom');
        return $this->redirect($url);
    }

    #[Route('/configurateur/charger/{id}', name: 'charger_configuration')]
    public function load(ConfigurationPC $configurationPC, ConfigurationPCRepository $configurationPCRepository, Request $request) {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Vérifie que la configuration appartient bien à l'utilisateur connecté
            if ($configurationPC->getUtilisateur() == $this->getUser()) {
                // Initialisation des variables
                $etape = 0;

                // Récupère la configuration de l'utilisateur
                $configuration = $configurationPCRepository->findOneBy(['utilisateur' => $this->getUser(), 'id' => $configurationPC->getId()]);

                if ($configuration) {
                    // Récupère les produits correspondant à la configuration
                    $configurationSession = [];
                    foreach ($configuration->getProduitConfigs() as $produitConfig) {
                        $produitConfig->getProduit()->getDesignation(); // Produit non initialisé si je n'accède pas a un des ses attributs (Symfony Lazy mode)
                        $etape = $produitConfig->getEtape();
                        $configurationSession[$etape] = $produitConfig->getProduit();
                    }

                    // Stocke la configuration en session
                     $request->getSession()->set('configuration', $configurationSession);

                     // Ajoute un message flash
                     $this->addFlash('success', 'Votre configuration à bien été chargée !');
                } else {
                    // Ajoute un message flash
                    $this->addFlash('danger', 'Vous n\'avez enregistré aucune configuration !');
                }
            } else {
                // Ajoute un message flash
                $this->addFlash('danger', 'Vous ne pouvez pas charger une configuration qui ne vous appartient pas !');
            }

        } else {
            // Ajoute un message flash
            $this->addFlash('danger', 'Vous devez être connecté pour charger une configuration !');
        }

         // Redirige vers l'étape suivante, ou vers le résumé de la configuration
         if ($etape != MAX_ETAPES) {
            return $this->redirectToRoute('configurateur');
        } else {
            return $this->redirectToRoute('recapitulatif_configuration');
        }
    }

    #[Route('/configurateur/supprimerConfig/{id}', name: 'supprimer_configuration')]
    public function delete(ConfigurationPC $configurationPC, ConfigurationPCRepository $configurationPCRepository, EntityManagerInterface $entityManager, Request $request) {
        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {
            // Vérifie que la configuration appartient bien à l'utilisateur connecté
            if ($configurationPC->getUtilisateur() == $this->getUser()) {
                // Récupère le nom de la configuration
                $nom = $configurationPC->getNom();

                // Supprime la configuration
                $entityManager->remove($configurationPC);
                $entityManager->flush();

                // Ajoute un message flash
                $this->addFlash('success', 'La configuration ' . mb_strtoupper($nom) . ' à bien étée supprimée !');

            } else {
                // Ajoute un message flash
                $this->addFlash('danger', 'Vous ne pouvez pas supprimer une configuration qui ne vous appartient pas !');
            }

        } else {
            // Ajoute un message flash
            $this->addFlash('danger', 'Vous devez être connecté pour supprimer une configuration !');
        }

        // Redirige vers le configurateur
        return $this->redirectToRoute('configurateur');
    }

    #[Route('/configurateur/reset', name: 'reset_configuration')]
    public function reset(Request $request) {
        $request->getSession()->remove('configuration');

        // Redirige vers le configurateur
        return $this->redirectToRoute('configurateur');
    }

    #[Route('/configurateur/recapitulatif', name: 'recapitulatif_configuration')]
    public function summary(CommandeRepository $commandeRepository, Request $request){
        // Initialisation des variables
        $composants = [];
        $panierVide = true;

        // Récupère la configuration stockée en session
        $configuration = $request->getSession()->get('configuration');

        // Récupère le total de la configuration
        $totalConfiguration = 0;
        if ($configuration) {
            foreach ($request->getSession()->get('configuration') as $produit) {
                if ($produit != null) {
                    $totalConfiguration += $produit->getprix();
                }
            }
        }

        // Vérifie qu'un utilisateur est connecté
        if ($this->getUser()) {

            // Récupère la commande active de l'utilisateur.
            $utilisateur = $this->getUser();
            $commande = $commandeRepository->findOneBy([
                'utilisateur' => $utilisateur->getId(),
                'etat' => "panier"
            ]);

            // Vérifie si la commande en cours est vide
            if ($commande && count($commande->getProduitCommandes()) > 0) {
                $panierVide = false;
            }
        }

        // Vérifie si il y à au moins un composant dans la configuration
        if ($configuration) {
            foreach (array_slice($configuration, 0, 8) as $composant) {
                if ($composant != null) {
                    $composants[] = $composant;
                }
            }
        }

        // Instancie un formulaire de type ConfigurationPCType
        $form = $this->createForm(ConfigurationPCType::class, null);

        return $this->render('configurateur/summary.html.twig', [
            'composants' => $composants,
            'totalConfiguration' => $totalConfiguration,
            'panierVide' => $panierVide,
            'formulaire' => $form
        ]);
    }

    // Retourne la liste des produits compatible avec ceux de l'étape spécifiée en comparant une ou plusieurs caractéristiques techniques
    // spécifique du produit de la configuration de l'étape spécifiée à une ou plusieurs caractéristiques des produits de l'étape en cours
    public function checkCompatibility(Request $request, ProduitCaracteristiqueTechniqueRepository $produitCtRepository, $produits, array $caracteristiquesAVerifier) {
        // Vérifie qu'une configuration existe en session, sinon, en crée une configuration vide
        // Empèche une erreur si on passe la 1ere étape sans avoir ajouté de produits
        if (!$request->getSession()->get('configuration')) {
            $request->getSession()->set('configuration', []);
        }

        // Pour chaque étape...
        $ListesProduitsCompatibles = [];
        foreach($caracteristiquesAVerifier as $etape => $correspondanceCaracterstique) {
            if (array_key_exists($etape, $request->getSession()->get('configuration'))) {
                // Vérifie que le produit de l'étape n'est pas null (étape passée)
                if ($request->getSession()->get('configuration')[$etape] != null) {
                    // Récupère les caractèristiques techniques du produit à vérifier de la configuration
                    $produitSource = $request->getSession()->get('configuration')[$etape];
                    $produitSourceCt = $produitCtRepository->findBy(['produit' => $produitSource]);

                    // Vérifie chaque caractéristique spécifiée
                    $ListesProduitsEtapeCompatibles = [];
                    foreach ($caracteristiquesAVerifier[$etape] as $caracteristiqueSource => $caracteristiqueCible) {
                        // Récupère les valeur des caractéristiques du produit à vérifier pour la caractéristique spécifiée
                        $valeursCaracteristiquesTechniques = [];
                        foreach($produitSourceCt as $produitCaracteristiqueTechnique) {
                            if ($produitCaracteristiqueTechnique->getCaracteristiqueTechnique()->getNom() == $caracteristiqueSource) {
                                $valeursCaracteristiquesTechniques[] = $produitCaracteristiqueTechnique->getValeur();
                            }
                        }

                        // Crée la liste des produits compatibles avec cette caractéristique
                        $produitsCompatibles = [];
                        foreach ($produits as $produit) { // Itère chaque produit
                            foreach ($produit->getCaracteristiquesTechniques() as $produitCaracteristique) { // Itère chaque caractéristique technique de chaque produit
                                if ($produitCaracteristique->getCaracteristiqueTechnique()->getNom() == $caracteristiqueCible) { // Si son nom est celui spécifié...
                                    foreach ($valeursCaracteristiquesTechniques as $valeur) { // Itère les valeurs des caractéristiques compatibles...
                                        if (ctype_digit($valeur)) { // Si la valeur est une chaîne de caractères uniquement constituée de nombres
                                            $valeurInt = intval($valeur); // Converti la chaîne de caractère en nombre entier
                                            if ($valeurInt >= intval($produitCaracteristique->getValeur())) { // Vérifie que la valeur est compatible
                                                $produitsCompatibles[] = $produit; // Ajoute le produit en tant que produit compatible
                                            }
                                        } else if (substr($valeur, -3) == " mm") { // Si la valeur se termine par " mm"
                                            $valeurInt = intval(substr($valeur, 0, -3)); // Récupère la valeur numérique et la converti en nombre entier
                                            if ($valeurInt >= intval(substr($produitCaracteristique->getValeur(), 0, -3))) { // Vérifie que la valeur est compatible
                                                $produitsCompatibles[] = $produit; // Ajoute le produit en tant que produit compatible
                                            }
                                        } else {
                                            if ($valeur == $produitCaracteristique->getValeur()) { // Vérifie que la valeur est compatible
                                                $produitsCompatibles[] = $produit; // Ajoute le produit en tant que produit compatible
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Ajoute la liste des produits compatibles avec cette caractéristique au tableau global de l'étape
                        $ListesProduitsEtapeCompatibles[] = $produitsCompatibles;
                    }

                    // Crée la liste des produits compatibles avec cette étape
                    $produitsCompatibles = [];
                    foreach ($produits as $produit) { // Itère chaque produit
                        $compatible = true; // Considère le produit comme compatible
                        foreach ($ListesProduitsEtapeCompatibles as $listeProduitsCompatibles) { // Itère la liste des produits compatible avec chaque caractéristique
                            if (!in_array($produit, $listeProduitsCompatibles)) { // Si le produit n'est pas compatible avec une caractéristique
                                $compatible = false; // Considère le produit comme non compatible
                                break; // Arrete la boucle
                            }
                        }
                        if ($compatible) { // Si le produit est compatible avec toutes les caractéristiques
                            $produitsCompatibles[] = $produit; // Ajoute le produit en tant que produit compatible
                        }
                    }

                    // Ajoute la liste des produits compatibles avec cette caractéristique à cette étape au tableau global
                    $ListesProduitsCompatibles[] = $produitsCompatibles;
                }
            }
        }

        // Crée la liste des produits compatibles avec toutes les étapes
        $produitsCompatibles = [];
        foreach ($produits as $produit) { // Itère chaque produit
            $compatible = true; // Considère le produit comme compatible
            foreach ($ListesProduitsCompatibles as $listeProduitsCompatibles) { // Itère la liste des produits compatible avec chaque étape
                if (!in_array($produit, $listeProduitsCompatibles)) { // Si le produit n'est pas compatible avec une étape
                    $compatible = false; // Considère le produit comme non compatible
                    break; // Arrete la boucle
                }
            }
            if ($compatible) { // Si le produit est compatible avec toutes les étapes
                $produitsCompatibles[] = $produit; // Ajoute le produit en tant que produit compatible
            }
        }

        return $produitsCompatibles;
    }
}

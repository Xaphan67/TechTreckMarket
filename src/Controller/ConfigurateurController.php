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
    #[Route('/configurateur/creer/{etape?}', name: 'configurateur')]
    public function index(int $etape = null, CategorieRepository $categorieRepository, ProduitRepository $produitRepository, ProduitCaracteristiqueTechniqueRepository $produitCtRepository, ConfigurationPCRepository $configurationPCRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // Initialisation des variables
        $produits = [];
        $configurations = [];
        $configurationsPrixTotal = [];
        if (!$etape) {
            $etape = 1;
        }

        // Récupère la configuration stockée en sesison
        $configuration = $request->getSession()->get('configuration');

        // Si l'étape à afficher dépasse le nombre total d'étapes, affiche la dernière étape
        if ($etape > MAX_ETAPES) {
            $etape = MAX_ETAPES;
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
                $produits += $produitRepository->findBy([
                        'categorie' => $sousCategorie,
                        "archive" => false
                    ]);
            }
        } else {
            // Récupère les produits correspondant à la catégorie
            $produits = $produitRepository->findBy([
                    'categorie' => $categorie,
                    "archive" => false
                ]);
        }

        // Vérifications à chaque étape
        // Tableau clé => valeur
        // clé = numéro de l'étape à vérifier
        // valeur = tableau de comparaison : caractéristique sur le produit déja existant sur la config => caractéristique du produit à ajouter
        $caracteristiquesAVerifier = [];
        switch ($etape) {
            case 1:
                $caracteristiquesAVerifier = [
                    2 => [
                        "Format de carte mère" => "Format de carte mère"
                    ],
                    6 => [
                        "Longueur" => "Longueur max. carte graphique"
                    ],
                ];
                break;
            case 2:
                $caracteristiquesAVerifier = [
                    1 => [
                        "Format de carte mère" => "Format de carte mère"
                    ],
                    3 => [
                        "Support du processeur" => "Support du processeur"
                    ],
                    5 => [
                        "Fréquence(s) Mémoire" => "Fréquence(s) Mémoire",
                        "Nombre de barrettes" => "Nombre de slots mémoire"
                    ],
                    6 => [
                        "Bus" => "Type de connecteur(s) graphique"
                    ],
                    7 => [
                        "Interface avec l'ordinateur" => "Connecteurs pour disques durs"
                    ]
                ];
                break;
            case 3:
                $caracteristiquesAVerifier = [
                    2 => [
                        "Support du processeur" => "Support du processeur"
                    ],
                    4 => [
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
                    ],
                    8 => [
                        "Connecteurs" => "Connecteur alimentation"
                    ]
                ];
                break;
            case 7:
                $caracteristiquesAVerifier = [
                    2 => [
                        "Connecteurs pour disques durs" => "Interface avec l'ordinateur"
                    ],
                    8 => [
                        "Connecteurs" => "Interface avec l'ordinateur"
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
        $produits = $this->checkCompatibility($request, $produitCtRepository, $produits, $etape, $caracteristiquesAVerifier);

        // Crée la pagination pour la liste des produits
        $produitsPagination = $paginator->paginate(
            $produits, // Contenu à paginer
            $request->query->getInt('page', 1), // Page à afficher
            10 // Limite par page
        );

        $produits = $produitsPagination;

        // Calcul le prix total de la configuration
        $totalConfiguration = 0;
        if ($configuration) {
            foreach ($configuration as $produit) {
                $totalConfiguration += $produit->getprix();
            }
        }

        // Instancie un formulaire de type ConfigurationPCType
        $form = $this->createForm(ConfigurationPCType::class, null);

        // Récupère les configurations enregistrés pour l'utilisateur
        if ($this->getUser()) {
            $configurations = $configurationPCRepository->findBy(['utilisateur' => $this->getUser()], ['nom' => 'ASC']);

            // Rédcupère le prix de chaque configuration
            foreach ($configurations as $configurationSauvee) {
                $totalConfig = 0;
                foreach ($configurationSauvee->getProduitConfigs() as $produitConfig) {
                    $totalConfig += $produitConfig->getProduit()->getPrix();
                }
                $configurationsPrixTotal[$configurationSauvee->getId()] = $totalConfig;
            }
        }

        return $this->render('configurateur/index.html.twig', [
            'etape' => $etape,
            'etapeFinale' => MAX_ETAPES,
            'infosEtapes' => $infosEtapes,
            'produits' => $produits,
            'configuration' => $configuration,
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

        // Redirige vers la prochaine étape non complète
        $prochaineEtape = $etape;
        foreach (array_keys($configuration) as $etapeConfiguration) {
            if ($etapeConfiguration >= $etape && $etapeConfiguration <= $prochaineEtape) {
                $prochaineEtape += 1;
            }
        }
        return $this->redirectToRoute('configurateur', ['etape' => $prochaineEtape]);
    }

    #[Route('/configurateur/supprimer/{etape}', name: 'supprimer_configurateur')]
    public function remove(int $etape, Request $request)
    {
        // Enregistre l'url d'entrée dans une variable en session
        $request->getSession()->set('urlFrom', $request->headers->get('referer'));

        // Récupère la configuration stockée en session
        $configuration = $request->getSession()->get('configuration');

        // Supprime le produit de la configuration
        unset($configuration[$etape]);

        // Stocke la configuration en session
        $request->getSession()->set('configuration', $configuration);

        // Redirige vers l'url d'entrée
        $url = $request->getSession()->get('urlFrom');
        $request->getSession()->remove('urlFrom');
        return $this->redirect($url);
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
                            // Récupère le nom de la configuration à partir du formulaire
                            $nomConfiguration = $form->getData()['nom'];

                            // Récupère la configuration de l'utilisateur ayant le même nom, si elle existe
                            $configuration = $configurationPCRepository->findOneBy([
                                'utilisateur' => $this->getUser(),
                                'nom' => $nomConfiguration
                            ]);

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
                                $prod = $produitRepository->findOneBy(['id' => $nouveauProduit->getId()]);
                                $configuration->addProduitConfig(new ProduitConfig($prod, 1, $etape));
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

        // Redirige vers le configurateur
        return $this->redirectToRoute('configurateur');
    }

    #[Route('/configurateur/supprimerConfig/{id}', name: 'supprimer_configuration')]
    public function delete(ConfigurationPC $configurationPC, EntityManagerInterface $entityManager) {
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
                $totalConfiguration += $produit->getprix();
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

        // Récupère les composants de la configuration
        if ($configuration) {
            foreach (array_slice($configuration, 0, 8) as $composant) {
                    $composants[] = $composant;
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
    public function checkCompatibility(Request $request, ProduitCaracteristiqueTechniqueRepository $produitCtRepository, $produits, $etapeActuelle, array $caracteristiquesAVerifier) {
        // Pour chaque étape...
        $ListesProduitsCompatibles = [];
        foreach(array_keys($caracteristiquesAVerifier) as $etape) {
            // Vérifie que la clé correspondant à l'étape existe
            if (array_key_exists($etape, $request->getSession()->get('configuration'))) {
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
                                        if (($etape < $etapeActuelle && $valeurInt >= intval($produitCaracteristique->getValeur())) || ($etape > $etapeActuelle && $valeurInt <= intval($produitCaracteristique->getValeur()))) { // Vérifie que la valeur est compatible
                                            $produitsCompatibles[] = $produit; // Ajoute le produit en tant que produit compatible
                                        }
                                    } else if (substr($valeur, -3) == " mm") { // Si la valeur se termine par " mm"
                                        $valeurInt = intval(substr($valeur, 0, -3)); // Récupère la valeur numérique et la converti en nombre entier
                                        if (($etape < $etapeActuelle && $valeurInt >= intval(substr($produitCaracteristique->getValeur(), 0, -3))) || ($etape > $etapeActuelle && $valeurInt <= intval(substr($produitCaracteristique->getValeur(), 0, -3)))) { // Vérifie que la valeur est compatible
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

# Tech Treck Market
Site E-Commerce spécialisé dans la vente de matériel informatique

## Description
Ce prjet à été réalisé dans le cadre de ma formation de développeur Web et Web mobile. Il s'agit de mon projet personnel présenté à mon examen de fin de formation.

## Fonctionnalités
- Page d'accueil regroupant les derniers produits ajoutés (nouveautées), les catégories principales et les sous-catégories choisies via l'interface d'administration, ainsi qu'une liste de marques chosisies aléatoirement (jusqu'a 9).
- Fonction de recherche globale par nom de produit ou marque.
- Liste des sous-catégories de chaque catégorie principale.
- Liste des produits de chaque catégorie, avec options de filtrage par nom, marque, prix et disponibilité, et possibilité de tri par nom, marque et prix.
- Fiche produit avec résumé du produit, descriptif, descriptif détaillé, caractéristiques techniques, avis clients, prix et formulaire d'ajout au panier.
- Espace client pour les utilisateurs enregistrés.
- Modification des données personnelles, du mot de passe utilisateur et suppression du compte.
- Récapitulatif des commandes en cours et historique des commandes passées, avec possibilité de génération de facture pour chaque commande.
- Gestion des adresses de facturation et de livraison, avec possibilité de choix d'une adresse de chaque en tant que préférée.
- Panier récapitulatif des achats du client, en vue d'une commande.
- Processus guidé de commande.
- Configurateur PC permettant la création d'une configuration guarantissant des produits compatibles les uns avec les autres.
- Possibilité de sauvegarder et de chanrger des configurations PC.
- Récaptiulatif d'une configuration PC permettant de la transformer en panier.
- Interface d'administration du site permettant l'ajout de produit (et autres données nécessaires au fonctionnement du site) et la validation de commandes.

## Installation

### Prérequis
- PHP
- Composer
- Symfony CLI

### Procédure
- Clonez l'intégralité du repository dans le dossier de votre choix
- Utilisez la commande `composer install`
- Importez la base de donnée contenue dans le fichier `sfEcommerce.sql`
- Modifiez le fichier `.env` avec les informations de connexion concernant votre base de donnée

### Démarrage
- Assurez-vous que votre serveur de base de donnée est démarré
- Utilisez la commande `symfony serve -d` pour démarrer le serveur local de Symfony
- Rendez-vous à l'adresse du serveur local (127.0.0.1:8000 par défaut) pour afficher l'application

## Axes d'amélioration
- Ajout d'une solution de paiement (Stripe).
- Amélioration du configurateur pour la gestion de plusieurs produits par étape quand nécessaire.
- Interface d'administration personnalisée.

## Captures d'écran

### Page d'accueil
![Image représentant la page d'accueil de l'application](/doc/Accueil.png)

### Liste des produits
![Image représentant la liste des produits d'une catégorie](/doc/ListeProduits.png)

### Fiche produit
![Image représentant la fiche d'un produit](/doc/FicheProduit.png)

### Panier
![Image représentant le panier](/doc/Panier.png)
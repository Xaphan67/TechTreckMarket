-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Listage de la structure de la base pour sfecommerce
CREATE DATABASE IF NOT EXISTS `sfecommerce` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sfecommerce`;

-- Listage de la structure de table sfecommerce. adresse_facturation
CREATE TABLE IF NOT EXISTS `adresse_facturation` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `numero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_rue` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferee` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D9E5A8D5FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_D9E5A8D5FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.adresse_facturation : ~4 rows (environ)
INSERT INTO `adresse_facturation` (`id`, `utilisateur_id`, `numero`, `type_rue`, `rue`, `code_postal`, `ville`, `preferee`) VALUES
	(9, 1, '2', 'place', 'des roseaux', '67980', 'HANGENBIETEN', 0),
	(10, 1, '2', 'avenue', 'des roseaux', '67980', 'HANGENBIETEN', 0),
	(17, NULL, '50', 'place', 'des cygongnes', '67000', 'STRASBOURG', 0),
	(20, 1, '20', 'place', 'des églises', '67980', 'LYON', 1);

-- Listage de la structure de table sfecommerce. adresse_livraison
CREATE TABLE IF NOT EXISTS `adresse_livraison` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `numero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_rue` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rue` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `preferee` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B0B09C9FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_B0B09C9FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.adresse_livraison : ~4 rows (environ)
INSERT INTO `adresse_livraison` (`id`, `utilisateur_id`, `numero`, `type_rue`, `rue`, `code_postal`, `ville`, `preferee`) VALUES
	(14, 1, '2', 'place', 'des roseaux', '67980', 'HANGENBIETEN', 1),
	(15, 1, '20', 'place', 'des roseaux', '67980', 'HANGENBIETEN', 0),
	(16, 1, '301', 'place', 'des roseaux', '67980', 'STRASBOURG', 0),
	(17, NULL, '2', 'place', 'des roseaux', '67980', 'HANGENBIETEN', 0);

-- Listage de la structure de table sfecommerce. avis
CREATE TABLE IF NOT EXISTS `avis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produit_id` int NOT NULL,
  `utilisateur_id` int DEFAULT NULL,
  `titre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `texte` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_publication` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8F91ABF0F347EFB` (`produit_id`),
  KEY `IDX_8F91ABF0FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_8F91ABF0F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  CONSTRAINT `FK_8F91ABF0FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.avis : ~3 rows (environ)
INSERT INTO `avis` (`id`, `produit_id`, `utilisateur_id`, `titre`, `texte`, `date_publication`) VALUES
	(1, 1, 1, 'Titre de l\'avis', 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Libero perferendis suscipit itaque saepe reiciendis nostrum quis nihil molestias earum. Fuga explicabo ut eos nobis facilis sunt eveniet voluptates. Autem, aliquid? Lorem ipsum dolor sit amet consectetur adipisicing elit. Sint, libero facilis earum maiores accusamus, odio quaerat vitae a dignissimos praesentium consectetur quos. Fuga excepturi cum, perspiciatis saepe sapiente asperiores sunt!', '2023-12-26 16:01:08'),
	(2, 1, NULL, 'Mon titre', 'Pas mal, mais bon, pourrais être mieux !', '2023-12-29 02:20:22'),
	(3, 1, 1, 'Test', 'Test ajout d\'un avis', '2024-01-09 12:36:10');

-- Listage de la structure de table sfecommerce. caracteristique_technique
CREATE TABLE IF NOT EXISTS `caracteristique_technique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.caracteristique_technique : ~17 rows (environ)
INSERT INTO `caracteristique_technique` (`id`, `nom`) VALUES
	(3, 'Fréquence(s) Mémoire'),
	(4, 'Format de carte mère'),
	(5, 'Longueur max. carte graphique'),
	(6, 'Support du processeur'),
	(7, 'Nombre de barrettes'),
	(8, 'Nombre de slots mémoire'),
	(9, 'Type de connecteur(s) graphique'),
	(10, 'Bus'),
	(11, 'Nombre de connecteurs graphique'),
	(12, 'Type de slots d\'extension'),
	(13, 'Nombre de slots d\'extension'),
	(14, 'Longueur'),
	(15, 'Nombre de slots pour disque dur'),
	(16, 'Connecteurs pour disques durs'),
	(17, 'Interface avec l\'ordinateur'),
	(18, 'Connecteurs'),
	(19, 'Connecteur alimentation');

-- Listage de la structure de table sfecommerce. categorie
CREATE TABLE IF NOT EXISTS `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `categorie_parent_id` int DEFAULT NULL,
  `nom` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `accueil` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_497DD634DF25C577` (`categorie_parent_id`),
  CONSTRAINT `FK_497DD634DF25C577` FOREIGN KEY (`categorie_parent_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.categorie : ~13 rows (environ)
INSERT INTO `categorie` (`id`, `categorie_parent_id`, `nom`, `description`, `image`, `accueil`) VALUES
	(1, NULL, 'Ordinateurs', '<div>Dopez votre productivité ou améliorez votre jeu avec un ordinateur fixe ou portable parmi notre sélection. Pour un usage familial, un ordinateur multimédia fixe ou portable vous permettra de naviguer, regarder des films ou séries ou effectuer des tâches bureautiques courantes. Faisant la part belle aux cartes graphiques performantes et processeurs de dernière génération, nos PC fixes gaming et ordinateurs portables gamer vous apporteront toute la puissance et la fluidité nécessaires pour vous poncer les hits populaires et à venir.</div>', '01HNCT401EDPQT1QTNPW6QWDD7.webp', 1),
	(2, NULL, 'Composants', '<div>Nous vous proposons un large choix de composants PC pour faire évoluer votre PC fixe actuel ou monter un nouvel ordinateur. Epine dorsale du PC, la carte mère se fait greffer des composants clé comme la carte graphique et le processeur, auxquels s\'ajoutent les barrettes mémoire, l\'alimentation, la carte son ainsi que les éléments de stockage comme le SSD et le disque dur. Conçu dans une variété de designs, le boîtier PC vous permettra d\'abriter cet ensemble hardware. Du composant informatique pas cher au composant haut de gamme, nous vous proposons un très large choix de pièces détachées pour la mise à jour de votre ordinateur ou l\'assemblage de votre nouvelle configuration PC.</div>', '01HNCT4EEHRVHM8XTPEZ8YNDK7.webp', 1),
	(3, NULL, 'Périphériques', '<div>Nos spécialistes ont sélectionné pour vous un vaste choix de périphériques informatiques. Ecran, clavier, souris et enceintes PC ou encore imprimante pour exploiter au mieux votre ordinateur, nous vous invitons à découvrir tous nos produits. Volant et manette de jeux pour les gamers, onduleur et prise parafoudre pour protéger votre matériel informatique, tablette graphique pour les créatifs, webcam pour la communication, télécommande multimédia pour remplacer vos nombreuses télécommandes.</div>', '01HNCT4PCC7J7YYXEJBJEGYPZZ.webp', 1),
	(4, 2, 'Cartes graphique', '<div>Retrouvez sur Materiel.net une large sélection des <strong>meilleures cartes graphiques</strong>, équipées de GPU NVIDIA et AMD. Que vous soyez gamer ou professionnel de l\'image,&nbsp; les <strong>cartes vidéo de dernière génération</strong> sont incontournables pour profiter pleinement de vos applications et des jeux les plus récents, plonger dans la réalité virtuelle ou exploiter tout le potentiel des outils de graphisme et de modélisation 3D. Elles sauront délivrer la puissance de calcul nécessaire à produire des images d\'un réalisme à couper le souffle.</div>', '01HNCT53RDET6E09DR0A1FRVMW.webp', 1),
	(5, 2, 'Cartes mère', '<div>Exploitez tout le potentiel de vos composants PC avec une <strong>carte mère</strong> de dernière génération parmi notre sélection. La carte mère est l\'épine dorsale de votre ordinateur de bureau : c\'est sur cet élément essentiel que vont venir se greffer le processeur, la carte graphique, la RAM et le SSD entre autres. Que vous cherchiez à assembler un PC fixe gaming ou multimédia, choisissez votre <strong>carte mère gamer ou entrée de gamme</strong> parmi des marques phares, dont Asus, MSI et Gigabyte. Sélectionnez un format adapté à votre boîtier PC : les <strong>cartes mères mini-ITX et micro-ATX</strong> seront idéales pour des petites tours, le format ATX conviendra à des boitiers moyen et grand tour. N\'oubliez pas de prendre en compte le type de socket (Intel / AMD) accueillant le processeur, son évolutivité et le nombre de ports disponibles USB, SATA ou PCI-Express. Pour économiser sur des ensembles de composants, n\'hésitez pas à opter pour nos kits d\'évolution PC.</div>', '01HNCT5BADB3V1SP80AK9NTK6V.webp', 1),
	(6, 2, 'Processeurs', '<div>Améliorez les performances de votre ordinateur de bureau avec un <strong>processeur</strong> de dernière génération parmi notre sélection&nbsp; dont les gammes Core i5 et Core i7 pour carte mère au socket Intel, et les processeurs Ryzen 5 et Ryzen 7 pour carte mère au socket AMD. Véritable cerveau de votre ordinateur, le <strong>microprocesseur</strong> (ou <strong>CPU</strong> pour Central Processing Unit) joue une part importante dans la rapidité d\'exécution de votre PC. Vous souhaitez monter un PC fixe gamer ou simplement faire évoluer votre ordinateur actuel ? Classés en fonction de leur socket, les <strong>processeurs</strong> proposés contiennent toutes les informations sur les <strong>chipsets</strong> et les principales caractéristiques techniques dont le nombre de cœurs, la fréquence en GHz, la mémoire cache et la consommation. Couplé à une carte graphique GeForce RTX ou Radeon RX, le <strong>processeur PC</strong> moyen / haut de gamme vous permettra de vivre des expériences gaming optimales en mode HIGH et ULTRA ou de créer du contenu vidéo de haute qualité sans vaciller.</div>', '01HNCT7NS2M83DAB83X73M8YAT.webp', 1),
	(7, 2, 'Boîtiers', '<div>Assemblez votre ordinateur sur mesure avec un <strong>boîtier PC</strong> parmi notre sélection, dont la gamme de boitiers Corsair et les boitiers Cooler Master. Premier contact visuel avec le PC, le <strong>design du boitier</strong> se décline sous plusieurs divers formats : le mini boitier PC, le boitier ATX (moyen tour) ou encore le grand boitier (E-ATX). Equipant souvent un PC gamer haut de gamme ou un ordinateur gaming petit prix, les <strong>boitiers PC</strong> fenêtrés (ou vitrés) vous permettront d\'admirer l\'ensemble de vos composants, et de jour comme de nuit avec les boitiers à LED RGB. Avec ou sans alimentation PC, compatible watercooling, le boîtier gamer ou bureautique intégrera parfaitement votre décor tout en assurant la bonne aération de votre ordinateur.</div>', '01HNCT863DNJN36B1KKKEMF7A6.webp', 0),
	(8, 2, 'Refroidissement', '<div>Un composant au frais est un composant heureux ! Notre sélection de radiateurs et de ventilateurs offre à vos précieux processeurs et cartes graphiques les températures idéales pour s’exprimer à leur plein potentiel. Si vous désirez profiter d’un PC sans bruit, achetez un radiateur silencieux pour votre carte vidéo et choisissez pour votre processeur un bon ventirad CPU associé à une bonne pâte thermique. Nous avons sélectionné pour vous les meilleurs <strong>systèmes de refroidissement</strong> parmi les plus grandes marques (Cooler Master, Thermalright, Noctua, Scythe, Arctic Cooling et Prolimatech). Pour une <strong>machine bien tunée et bien refroidie</strong>, profitez également de nos radiateurs disques durs, radiateurs mémoires, et <strong>ventilateurs supplémentaires</strong>.</div>', '01HNCT8EQ7D3HJQBTD81F6JM5Y.webp', 0),
	(9, 8, 'Refroidissement Processeur', '<div>Nous vous présentons le meilleur du <strong>refroidissement processeur</strong> afin de compléter votre configuration PC. Pour améliorer la dissipation thermique, plusieurs systèmes s\'offrent à vous. Le watercooling AIO privilégie un système de refroidissement fermé par eau. À l\'inverse, le ventirad n\'opère pas de refroidissement liquide mais utilise un radiateur et l\'air pour évacuer la chaleur. Cooler Master, Be Quiet!, Noctua, choisissez votre <strong>radiateur pour processeur</strong> parmi une large sélection de marques, et optimisez le refroidissement de votre PC.</div>', '01HNCT8NE2Q2T76VHH1ZHC9G8A.webp', 0),
	(10, 2, 'Mémoire', '<div>Avec une barrette de <strong>RAM, ou mémoire PC</strong>, donnez un bon coup d\'accélérateur à votre ordinateur de bureau, votre Mac ou votre PC portable. Les logiciels et jeux les plus récents requièrent toujours plus de <strong>mémoire vive</strong>. Grâce à une gravure toujours plus fine sur les mémoires, chaque nouveau standard de RAM propose des taux de transfert et des fréquences toujours plus élevés, ainsi que des tensions plus basses (jusqu\'à 1.1V pour la DDR5). Pour éviter les mauvaises surprises, vérifiez que la génération de RAM est compatible avec la carte mère de votre PC. Au format DIMM pour les ordinateurs fixes et SO-DIMM pour les portables, augmentez la taille de la mémoire en l\'équipant de barrettes de 8 Go, 16 Go voire 32 Go pour les PC gamer les plus puissants.</div>', '01HNCT8WGBMTWZYDD5XXJKQQ2E.webp', 1),
	(11, 2, 'Stockage', '<div>Nous avons sélectionné pour vous le meilleur du <strong>disque dur</strong> pour équiper votre Ordinateur de bureau, votre PC portable ou votre Mac. Pour des performances époustouflantes en lecture comme en transfert de données, nous vous conseillons vivement d\'opter pour un <strong>disque SSD</strong> ou SSHD avec une mémoire flash. Pensez-y pour donner une nouvelle jeunesse à ordinateur ou un PC Portable lent et un peu vieillissant. Nous vous proposons également une large gamme de <strong>disque dur interne</strong> pour stocker vos données et <strong>disque dur externe</strong> pour le transport de toutes vos fichiers lors de vos déplacements. Vous pourrez choisir et comparer votre disque dur parmi les plus grandes marques : <strong>Western Digital</strong>, <strong>Seagate</strong>, <strong>Samsung</strong>, <strong>Crucial</strong>, <strong>OCZ</strong>, <strong>Intel</strong>, <strong>Kingston</strong> ou encore <strong>Toshiba</strong>.</div>', '01HNCT93ZGX2HJ3XH7P3M7X0CB.webp', 0),
	(12, 2, 'Alimentations', '<div>Assemblez votre ordinateur de bureau avec une <strong>alimentation PC puissante</strong> parmi notre sélection, dont les alimentations Be Quiet, Seasonic et Corsair. Fournissant de l\'énergie à tous les composants de votre boitier PC, le <strong>bloc d\'alimentation</strong> produit une puissance électrique qui est à déterminer en fonction de votre usage. Une puissance standard de 550 watts / 650 watts conviendra à un PC bureautique et multimédia, tandis qu\'une <strong>alimentation PC gamer</strong> de 1000 watts enverra le courant électrique requis par des machines gourmandes en calcul et en traitement graphique. Au format ATX ou SFX suivant la carte mère ou le format de la tour, le <strong>boitier d\'alimentation</strong> est rythmé par des innovations régulières en matière de rendement énergétique et de refroidissement. Incontournable en matière d\'alimentation PC, la <strong>certification 80 PLUS</strong> permet de garantir que 80% de l\'énergie produite est envoyée à la machine.</div>', '01HNCT9BB5TT3YMQ7826V3YERC.webp', 1),
	(13, 11, 'Disque dur interne', '<div>Retrouvez le meilleur du <strong>disque dur interne</strong> pour PC de bureau ou ordinateur portable. Deux formats sont disponibles : 3.5 pouces et 2.5 pouces. En fonction de vos besoins de stockage et de votre type de PC (bureautique, gamer, vidéo, graphisme), choisissez le <strong>disque dur</strong> en fonction de l\'interface adaptée : SATA ou SAS. Déclinés en différentes capacités - du disque dur 1 To / 2 To / 4 To à plus de 16 To - nos spécialistes sont à l\'écoute du marché pour vous proposer les <strong>disques durs</strong> dotés des dernières technologies parmi les plus grands constructeurs. Simple à installer et évolutif, tirez le meilleur parti de votre PC avec ce support de stockage. Très complémentaires, combinez votre <strong>HDD</strong> avec un SSD interne pour booster les performances de votre PC.</div>', '01HNCT9NBMVH7G4W0PB5C9M3KP.webp', 0);

-- Listage de la structure de table sfecommerce. commande
CREATE TABLE IF NOT EXISTS `commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `civilite` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_commande` date NOT NULL,
  `prix_produits` json NOT NULL,
  `etat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_facturation` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_livraison` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6EEAA67DFB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_6EEAA67DFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.commande : ~11 rows (environ)
INSERT INTO `commande` (`id`, `utilisateur_id`, `civilite`, `prenom`, `nom`, `date_commande`, `prix_produits`, `etat`, `adresse_facturation`, `adresse_livraison`) VALUES
	(8, 1, 'madame', 'Solange', 'FALDA', '2023-12-22', '{"2": "50.00", "3": "650.00"}', 'expédiée', '2, place des roseaux - 67980 HANGENBIETEN', '2, place des roseaux - 67980 HANGENBIETEN'),
	(10, 1, 'monsieur', 'Cédric', 'FALDA', '2023-12-26', '{"1": "75.95"}', 'expédiée', '30, place des églises - 67980 LYON', '301, place des roseaux - 67980 STRASBOURG'),
	(11, 1, 'monsieur', 'Cédric', 'FALDA', '2023-12-28', '{"2": "50.00"}', 'expédiée', '30, place des églises - 67980 LYON', '2, place des roseaux - 67980 HANGENBIETEN'),
	(12, 1, 'monsieur', 'Cédric', 'FALDA', '2023-12-28', '{"2": "50.00"}', 'expédiée', '30, place des églises - 67980 LYON', '2, place des roseaux - 67980 HANGENBIETEN'),
	(13, 1, 'monsieur', 'Cédric', 'FALDA', '2023-12-28', '{"2": "50.00"}', 'expédiée', '30, place des églises - 67980 LYON', '2, place des roseaux - 67980 HANGENBIETEN'),
	(14, 1, 'monsieur', 'Cédric', 'FALDA', '2023-12-28', '{"2": "50.00"}', 'expédiée', '30, place des églises - 67980 LYON', '2, place des roseaux - 67980 HANGENBIETEN'),
	(15, 1, 'monsieur', 'Cédric', 'FALDA', '2023-12-28', '{"1": "75.95"}', 'en cours de préparation', '2, place des roseaux - 67980 HANGENBIETEN', '2, place des roseaux - 67980 HANGENBIETEN'),
	(16, NULL, 'monsieur', 'Baisangour', 'ALIEV', '2023-12-29', '{"1": "75.95", "4": "28.69"}', 'expédiée', '50, place des cygongnes - 67000 STRASBOURG', '2, place des roseaux - 67980 HANGENBIETEN'),
	(18, 5, '', '', '', '2023-12-29', '[]', 'panier', '', ''),
	(19, 1, 'monsieur', 'Cédric', 'FALDA', '2023-12-30', '{"1": "75.95", "4": "28.69"}', 'en cours de préparation', '20, place des églises - 67980 LYON', '2, place des roseaux - 67980 HANGENBIETEN'),
	(20, 1, '', '', '', '2024-01-01', '[]', 'panier', '', '');

-- Listage de la structure de table sfecommerce. configuration_pc
CREATE TABLE IF NOT EXISTS `configuration_pc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `nom` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_76971163FB88E14F` (`utilisateur_id`),
  CONSTRAINT `FK_76971163FB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.configuration_pc : ~4 rows (environ)
INSERT INTO `configuration_pc` (`id`, `utilisateur_id`, `nom`) VALUES
	(1, 5, 'PC Gamer'),
	(2, 5, 'Autre config'),
	(12, 1, 'test'),
	(13, 1, 'config 4 produits');

-- Listage de la structure de table sfecommerce. marque
CREATE TABLE IF NOT EXISTS `marque` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.marque : ~8 rows (environ)
INSERT INTO `marque` (`id`, `nom`, `logo`) VALUES
	(1, 'Gigabyte', 'gigabyte.webp'),
	(2, 'Asus', 'asus.webp'),
	(3, 'Corsair', 'corsair.webp'),
	(4, 'Intel', 'intel.webp'),
	(5, 'Be Quiet', 'be-quiet.webp'),
	(6, 'Seagate', 'seagate.webp'),
	(7, 'MSI', 'msi.webp'),
	(8, 'AMD', 'amd.webp'),
	(9, 'Phanteks', 'C000035444.webp');

-- Listage de la structure de table sfecommerce. messenger_messages
CREATE TABLE IF NOT EXISTS `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.messenger_messages : ~0 rows (environ)
INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
	(1, 'O:36:\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\":2:{s:44:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\";a:1:{s:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\";a:1:{i:0;O:46:\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\":1:{s:55:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\";s:21:\\"messenger.bus.default\\";}}}s:45:\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\";O:51:\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\":2:{s:60:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\";O:39:\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\":4:{i:0;s:41:\\"registration/confirmation_email.html.twig\\";i:1;N;i:2;a:3:{s:9:\\"signedUrl\\";s:178:\\"http://127.0.0.1:8000/verify/email?expires=1700004278&id=1&signature=%2FkUAuBvbuKEsI8plIQn%2FFIUWMM%2FNlWOA93j8xo%2BjjBE%3D&token=dvwQXPt8hQo8%2F9XFUNf2L7hhhUKbFuvYMhB1Y7s117Y%3D\\";s:19:\\"expiresAtMessageKey\\";s:26:\\"%count% hour|%count% hours\\";s:20:\\"expiresAtMessageData\\";a:1:{s:7:\\"%count%\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\":2:{s:46:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\";a:3:{s:4:\\"from\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:4:\\"From\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:20:\\"admin@e-commerce.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:16:\\"Admin E-Commerce\\";}}}}s:2:\\"to\\";a:1:{i:0;O:47:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:2:\\"To\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:58:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\";a:1:{i:0;O:30:\\"Symfony\\\\Component\\\\Mime\\\\Address\\":2:{s:39:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\";s:22:\\"cedric.falda@gmail.com\\";s:36:\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\";s:0:\\"\\";}}}}s:7:\\"subject\\";a:1:{i:0;O:48:\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\":5:{s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\";s:7:\\"Subject\\";s:56:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\";i:76;s:50:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\";N;s:53:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\";s:5:\\"utf-8\\";s:55:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\";s:25:\\"Please Confirm your Email\\";}}}s:49:\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\";i:76;}i:1;N;}}}s:61:\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\";N;}}', '[]', 'default', '2023-11-14 22:24:38', '2023-11-14 22:24:38', NULL);

-- Listage de la structure de table sfecommerce. produit
CREATE TABLE IF NOT EXISTS `produit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `marque_id` int NOT NULL,
  `categorie_id` int NOT NULL,
  `categorie_principale_id` int NOT NULL,
  `date_lancement` date NOT NULL,
  `designation` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `resume` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descriptif` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `descriptif_detaille` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_29A5EC274827B9B2` (`marque_id`),
  KEY `IDX_29A5EC27BCF5E72D` (`categorie_id`),
  KEY `IDX_29A5EC27D639D323` (`categorie_principale_id`),
  CONSTRAINT `FK_29A5EC274827B9B2` FOREIGN KEY (`marque_id`) REFERENCES `marque` (`id`),
  CONSTRAINT `FK_29A5EC27BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`),
  CONSTRAINT `FK_29A5EC27D639D323` FOREIGN KEY (`categorie_principale_id`) REFERENCES `categorie` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.produit : ~14 rows (environ)
INSERT INTO `produit` (`id`, `marque_id`, `categorie_id`, `categorie_principale_id`, `date_lancement`, `designation`, `resume`, `descriptif`, `photo`, `prix`, `stock`, `archive`, `descriptif_detaille`) VALUES
	(1, 1, 5, 2, '2023-10-21', 'Gigabyte B650 AORUS ELITE AX', 'AMD B650 - Socket AM5 - ATX - Raphael - Wi-Fi 6E - Compatible processeurs AMD Ryzen 7000', '<div>Conçue pour les gamers, la carte mère Gigabyte <strong>B650 AORUS ELITE AX</strong> vous permettra de profiter des performances des processeurs AMD Ryzen série 7000 sur socket AM5. Elle prend en charge la mémoire DDR5 ainsi que la norme PCIe 5.0 pour une bande passante améliorée, ainsi qu\'une connectique plus fournie ! Il ne vous reste plus qu\'à créer la configuration PC de jeu de vos rêves.&nbsp;</div>', '01HNCXG5GMGMDS5HE2XX3J1AGG.webp', 75.95, 17, 0, '<div><strong>Plateforme AMD AM5 pour Ryzen série 7000<br></strong><br></div><div>La carte mère Gigabyte B650 AORUS ELITE AX fait partie des cartes mères socket AM5 qui accompagnent la micro-architecture AMD Zen 4. Et avec elle, son lot de nouvelles fonctionnalités comme le support de la mémoire DDR5. Concentrant l\'essentiel de la connectique et connectivité nécessaires à tous les usages, elle permet de profiter des performances des processeurs Ryzen 7000 facilement.<br><br></div><div>AMD inaugure également les profils étendus pour l\'overclocking avec sa technologie AMD EXPO, développée pour permettre une prise en charge intuitive de l\'overclocking de tous les types de mémoire et d\'accéder à des performances redoutables.<br><br></div><div>Tandis que le passage à une autre plateforme peut se traduire par de nombreux changements, AMD a garanti que les solutions de refroidissement AM4 existantes continuent à être prises en charge, assurant ainsi une transition fluide vers AM5.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:741,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Processeurs/AMD/Ryzen%207000/800-ryzen7000-1.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Processeurs/AMD/Ryzen%207000/800-ryzen7000-1.jpg" width="800" height="741"><figcaption class="attachment__caption"></figcaption></figure></div><div><strong>Une solution technique de premier choix<br></strong><br></div><div>Afin de suivre la montée en puissance occasionnée par les processeur, ce modèle intègre un dispositif de refroidissement efficace. La Gigabyte B650 AORUS Elite AX embarque donc un large radiateur MOSFET, des caloducs de 8 mm pour une dissipation optimale de la chaleur et une plaque de renfort permettant de réduire de 30% les températures des MOSFET.<br><br></div><div>Comptez aussi sur la technologie Smart Fan 6 pour un contrôle intelligent ainsi que des dissipateurs Thermal Guard pour les slots SSD M.2 pour tempérer efficacement les ardeurs de votre processeur Ryzen et assurer un fonctionnement optimale et atteindre les meilleures performances possibles.</div>'),
	(2, 2, 5, 2, '2023-10-22', 'Test', 'Test', '<div>Descriptif</div>', '01HNCXGR733FH2ND6V75RD8JPP.webp', 50.00, 11, 1, NULL),
	(3, 1, 4, 2, '2023-11-05', 'Gigabyte GeForce RTX 4060 WINDFORCE OC', 'GeForce RTX 4060, PCI-Express 4.0 8x, 8 Go GDDR6, DLSS 3', '<div>La carte graphique Gigabyte GeForce RTX 4060 WINDFORCE OC met à disposition toutes les performances de l\'architecture NVIDIA Ada Lovelace et la puissance combinée du ray-tracing et du DLSS3 pour vous permettre de créer votre configuration PC gamer, qui vous fera vibrer en 1080p dans les meilleures conditions !</div>', '01HNCXH9KC7W55XN40686VNRJG.webp', 369.95, 20, 0, NULL),
	(4, 1, 5, 2, '2023-12-27', 'Test produit Gigabyte', 'Test', '<div>Descriptif</div>', '01HNCXHYG8XMS9GVPPQ1GE11M7.webp', 28.69, 3, 1, NULL),
	(5, 3, 7, 2, '2024-01-09', 'Corsair 3000D Airflow - Noir', 'Moyenne tour, ATX / E-ATX / Micro ATX / Mini ITX, Noir', '<div>Le boitier Corsair 3000D Airflow est un boitier simple et performant qui permet d\'accueillir votre configuration de jeu et de bien la refroidir. Ce boitier moyen tour est doté d\'un panneau avant optimisé pour le passage de l\'air, et il est aussi équipé de 2 ventilateurs AF120.</div>', '01HNCXJ95T3H4AD17K2A40BM5C.webp', 89.95, 15, 0, NULL),
	(6, 4, 6, 2, '2024-01-09', 'Intel Core i5 13600KF', '14 coeurs, 20 threads, 3.50 GHz, 24 Mo, Raptor Lake, BX8071513600KF', '<div>Le processeur Intel Core i5 13600KF débarque avec des fréquences boostées et un nombre de coeur en hausse par rapport à la génération précédente. Streaming, création, gaming : il sera à l\'aise en toutes circonstances grâce à l\'architecture hybride alliant P-Core et E-Core et surtout au support de la norme PCIe 5.0 et de la mémoire DDR5. Que vous soyez gamer ou créateur de contenu multimedia, la performance est au rendez-vous !</div>', '01HNCXJZZBX88QS5582MQDQ0KA.webp', 379.96, 20, 0, NULL),
	(7, 5, 9, 2, '2024-01-09', 'Be Quiet Pure Rock Slim 2', 'Simple tour, Cuivre et aluminium, 1150 / 1151 / 1155 / 1200 / 1700 et AM4, AM5', '<div>La conception du Pure Rock Slim est compatible avec les plateformes Intel 1150 / 1151 / 1155 / 1200/1700 et AMD AM4, AM5 et convient particulièrement aux mini PC grâce à sa conception asymétrique et compacte. Si vous êtes à la recherche d‘un ventirad avec le meilleur rapport prix/performance, ne cherchez plus... Le Pure Rock Slim est le choix idéal !</div>', '01HNCXKFG448MWTD914V27PQ9Y.webp', 29.95, 30, 0, NULL),
	(8, 3, 10, 2, '2024-01-09', 'Corsair Vengeance LPX Black DDR4 2 x 8 Go 3200 MHz CAS 16', 'RAM PC, DDR4, 16 Go, 3200 MHz - PC25600, 16-18-18-36, 1,35 Volts, CMK16GX4M2B3200C16', '<div>Corsair ne s\'est pas fait attendre avant de sortir ses premiers kits de mémoire DDR4 ! Découvrez la série Vengeance LPX qui vous offre plus de réactivité que la DDR3 ainsi que des performances excellentes sur le long terme, grâce notamment à une conception qui favorise le refroidissement du PCB.</div>', '01HNCXKTKWAQHHYSS7VNW43QE1.webp', 59.95, 50, 0, NULL),
	(9, 6, 13, 2, '2024-01-09', 'Seagate BarraCuda - 2 To - 256 Mo', 'Disque dur 2 To, 3.5", SATA/AHCI, 7200 tr/min, 256 Mo, ST2000DM008', '<div>Tirez le meilleur profit de votre stockage avec les disques durs BarraCuda de Seagate. Que vous souhaitiez conserver vos innombrables photos et souvenirs ou augmenter la capacité de votre PC de jeu, les disques BarraCuda évoluent avec vous.</div>', '01HNCXM565R6TNFDHYXQFNDD4V.webp', 72.95, 35, 0, NULL),
	(10, 1, 12, 2, '2024-01-09', 'Gigabyte GP-P750GM - Gold', 'Alimentation PC 750W, Modulaire, 80 PLUS Or, 2 x CPU', '<div>L\'alimentation PC Gigabyte GP-P750GM est idéale pour votre PC gamer avec sa puissance de 750W. Cetifiée 80 PLUS Or, elle montre un niveau d\'efficacité remarquable, elle possède aussi un système de refroidissement interne performant et silencieux. Grâce aux nombreux connecteurs, et sa modularité allez à l\'essentiel !</div>', '01HNCXN5T6DA2QMSHNBXNPCERF.webp', 109.94, 15, 0, NULL),
	(11, 7, 5, 2, '2024-01-10', 'MSI A520M A-PRO', 'Socket AM4, AMD A520, 1 port PCI-Express 16x, 3200 MHz, 1 port M.2 (SATA et PCIE), Micro-ATX', '<div>Les cartes mères MSI de série PRO sont pensées pour intégrer tous les PC. Elles sont conçues pour accueillir les processeurs AMD Ryzen et AMD Athlon sur socket AMD AM4 et pour offrir des performances fiables et des solutions professionnelles intelligentes qui vous rendront le travail plus facile. En résumé, la carte mère MSI A520M A-PRO est synonyme de stabilité, d\'efficacité et de longévité.</div>', '01HNCXNNB4K2NEJPVT00V2MMXY.webp', 75.95, 10, 0, '<div><strong>MSI A520M Pro : performances augmentées<br></strong><br></div><div>Pour les utilisateurs en recherche d\'un PC accessible, polyvalent pour travailler sans problème, le chipset AMD A520 se démarque comme la plateforme idéale tirer le meilleur des processeurs AMD Ryzen 3000 basé sur l\'architecture Zen 2 ainsi que les processeurs sur architecture Zen 3, ainsi de futures évolutions sont possible pour maintenir votre équipement à jour ! Fiable, stable, ces cartes mères disposent de l\'essentiel de connectivité et de bande passante pour satisfaire les besoins de tous les types d\'utilisateurs, que ce soit pour un usage au bureau ou à domicile.<br><br></div><div>La carte mère MSI A520M PRO se dote de nombreuses fonctionnalités qui la rendent idéale pour le montage d\'un PC bureautique/multimedia et à usage professionnel. Support de la mémoire DDR4 3200+, 4 ports Sata 6Gb/s, dissipateur M.2 Shield Frozr pour garder votre SSD M.2 à bonne température de fonctionnement : des caractéristiques qui lui assurent une stabilité et une fiabilité excellentes.<br><br></div><div>MSI a également doté sa carte mère d\'utilitaires logiciel pratiques : Click BIOS 5 pour une amélioration des performances, AUDIO Boost pour gérer ma qualité audio de manière optimale ou encore X-boost qui permet d\'augmenter les performances de stockage et des périphériques USB.</div><div>.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:300,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/500-prom2v2.jpg&quot;,&quot;width&quot;:500}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/500-prom2v2.jpg" width="500" height="300"><figcaption class="attachment__caption"></figcaption></figure></div>'),
	(12, 7, 5, 2, '2024-01-10', 'MSI PRO H610M-E DDR4', 'Intel H610 - Socket LGA1700 - Micro ATX - Alder Lake S - Compatible processeurs Intel Core 12ème génération - Support DDR4', '<div>La carte mère MSI PRO H610M-G DDR4 enrichit la série 600 sur socket LGA 1700 destinée aux processeurs Intel de 12ème génération. MSI propose sa série PRO, destinée aux configurations professionnelles et dotée de l\'essentiel en terme de connectique, connectivité et stockage. Le tout supporté par des composants fiables et robustes.</div>', '01HNCXP4G3EBC6W6V90NFFR9TH.webp', 95.95, 30, 0, '<div><strong>Chipset H610 : aux portes des processeurs Intel Alder Lake<br></strong><br></div><div>La MSI PRO H610M-E DDR4 fait partie des cartes mères de la <strong>série PRO</strong> qui aident les professionnels à améliorer leur productivité et leur efficacité grâce à des fonctionnalités intelligentes qui pourront les accompagner dans toutes leurs tâches. Leur composition est également à la hauteur des attentes des utilisateurs les plus exigeants et la qualité des composants utilisés est telle que ces cartes mères garantissent une durée de vie prolongée et des besoins de dépannages moindres.<br><br></div><div>Dans l\'optique d\'utiliser des processeurs intégrant de plus en plus en de coeurs, <strong>MSI</strong> a doté son modèle de l\'essentiel côté connectique t stockage : ports USB 3.0 pour les périphériques, slot M.2 pour votre SSD : fluidité et réactivité sont de mises pour vous accompagner aux quotidiens dans vos tâches professionnelles.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:420,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/h6xx/600-h610prod4-g.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/carte%20m%C3%A8re/msi/h6xx/600-h610prod4-g.jpg" width="600" height="420"><figcaption class="attachment__caption"></figcaption></figure></div>'),
	(13, 8, 6, 2, '2024-01-10', 'AMD Ryzen 7 7700X', 'Processeur 8 coeurs, 4.50 GHz, 32 Mo, AMD Zen 4, TDP 105 Watts, socket AM5, version boîte sans ventilateur', '<div>AMD passe à la vitesse "Zen 4" avec le processeur Ryzen 7 7700X. Pensés pour les gamers et les créateurs, les Ryzen Série 7000 offrent boost de fréquence et augmentation des performances en mono-thread pour permettre à votre configuration PC de s\'attaquer à n\'importe quel jeu ou workflow.</div>', '01HNCXPPACXZ7B2H3AT3YXAF7T.webp', 414.95, 20, 0, NULL),
	(14, 7, 4, 2, '2024-01-26', 'MSI GeForce RTX 4090 Gaming X TRIO', 'GeForce RTX 4090, PCI-Express 16x, 24 Go GDDR6X, DLSS 3', '<div>Avec comme promesse une expérience gaming fluide jusqu\'en 8K, la carte graphique MSI GeForce RTX 4090 Gaming X TRIO déploie les performances d\'un monstre de puissance. Avec des capacités élevées à un niveau extrême grâce à l\'architecture NVIDIA Ada Lovelace et supportée par 24 Go de mémoire GDDR6X, elle sera une alliée de choix pour des sessions de jeu extrêmes ou pour la création de contenu vidéo.</div>', '01HNCX1DD9HWS443GX381EP26D.webp', 2429.95, 20, 0, '<div><strong>RTX 4090 pour jouer en 4K<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, la carte graphique MSI GeForce RTX 4090 Gaming X TRIO inaugure un saut de performances excellent par rapport à la génération précédente. Coeurs Tensor de quatrième génération pour l\'Intelligence artificelle, coeurs RT de troisième génération pour des capacités de ray-tracing encore plus fines : tout est réuni pour une expérience de jeu à un niveau extrême !<br><br></div><div>Elle s\'appuie également sur pas moins de 24 de mémoire GDDR6X et 16 384 coeurs CUDA qui libéreront le plein potentiel de ce GPU de pointe. Des telles performances requiert un refroidissement particulièrement soigné, que la Gaming X Trio réussit avec succès. Les ventilateurs Torx 5.0 redirigent l\'air vers le système de refroidissement Tri Frozr 3 de dernière génération, épaulés par des caloducs Core Pipe méticuleusement placés sur le GPU pour maximiser la dissipation de chaleur, vous obtenez une carte bien régulée et capable de vous fournir un expérience de jeu des plus fluides.<br><br></div><div>Enfin, mettez un peu de lumière dans votre machine en personnalisant et contrôlant le rétroéclairage RGB de la carte graphique et en le synchronisant avec celui de vos autres composants compatibles avec Mystic Light.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4090gaming.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4090gaming.jpg" width="800" height="600"><figcaption class="attachment__caption"></figcaption></figure></div><div><strong>NVIDIA Ada Lovelace et DLSS3 : toujours plus loin<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, NVIDIA ne se contente pas seulement de proposer un gain de performances conséquent par rapport à la génération précédente, mais également une amélioration des technologies RTX et surtout l\'inauguration du DLSS 3. Optimisé par les coeurs Tensor de quatrième génération et l\'accélérateur de flux optiques des GPU GeForce RTX série 40, le DLSS 3 exploite l\'IA pour générer des images additionnelles de haute qualité sans altérer la qualité ni la réactivité des images.<br><br></div><div><strong>Les outils pour votre créativité<br></strong><br></div><div>Et parce que les RTX série 4000 visent également les porteurs de projets créatifs, leurs performances sont à même de faciliter vos rendus 3D, montages vidéo et conceptions graphique. Grâce aux capacités d\'accélération dans les principales applications de création du marché ainsi qu\'aux pilotes NVIDIA Studio conçus pour garantir un maximum de stabilité, vous profitez d\'un panel d\'outils exclusifs exploitant la puissance de RTX pour un environnement de création productif assisté par l\'IA.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:800,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/800-rtx4000-3.jpg&quot;,&quot;width&quot;:900}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/800-rtx4000-3.jpg" width="900" height="800"><figcaption class="attachment__caption"></figcaption></figure></div>'),
	(15, 2, 4, 2, '2024-01-30', 'Asus Phoenix Radeon RX 550 - 2 Go (PH-550-2G)', 'Radeon RX 550, PCI-Express 3.0, 2 Go GDDR5', '<div>La carte graphique Asus Phoenix Radeon RX 550 a été pensée dans un format compact idéal pour les jeux PC avec une forte scène eSport : Overwtach, Dota 2, CSGo... Les performances qu\'il vous faut pour un budget contenu !</div>', '01HND9N7DVFJGWCANKX5C2WKTX.webp', 89.95, 20, 0, '<div><strong>Nouvelle architecture AMD : Polaris !<br></strong><br></div><div>Nouvelle déclinaison de la gamme des AMD Radeon RX 500, la Asus Phoenix RX 550 2 Go s’appuie sur l\'architecture AMD Polaris. Utilisant une nouvelle technologie de gravure en 14 nanomètres, la Asus RX 550 affiche son objectif : rendre accessible le jeu PC au plus grand nombre !<br><br></div><div>Affichant un prix défiant toute concurrence, la RX 550 vous permettra de jouer aux derniers titres de eSport en toute fluidité.<br><br></div><div>Immergez vous dans la nouvelle génération de jeux PC en douceur avec un prix contenu et aux performances intéressantes. La Asus Radeon RX 550 Pulse embarque 2 Go de mémoire de type DDR5 elle vous apportera une fluidité et un confort de jeu optimal.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:300,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201802220163_d0.jpg&quot;,&quot;width&quot;:400}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/oproducts/AR201802220163_d0.jpg" width="400" height="300"><figcaption class="attachment__caption"></figcaption></figure><br><br></div><div><strong>IP5X, 0 dB fan... le savoir faire ASUS !<br></strong><br></div><div>La <strong>Asus Phoenix Radeon RX 550</strong>intègre les dernières innovations ASUS et tout le savoir faire gaming de la marque. Asus produit ses cartes via le technologie industrielle Auto-Extreme qui garantit un processus 100% automatique incluant matériau premium et standard de qualité au top.<br><br></div><div>Cette carte graphique ASUS embarque des ventilateurs IPX5 brevetés. Ils délivreront un flux d\'air maximal et résistant à la poussière pour une durée de vie prolongée au maximum.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:300,&quot;url&quot;:&quot;https://media.materiel.net/oproducts/AR201802220163_d1.jpg&quot;,&quot;width&quot;:400}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/oproducts/AR201802220163_d1.jpg" width="400" height="300"><figcaption class="attachment__caption"></figcaption></figure></div>'),
	(16, 2, 4, 2, '2024-01-30', 'Asus GeForce RTX 4060 Ti DUAL OC', 'GeForce RTX 4060 Ti, PCI-Express 4.0 x8, 8 Go GDDR6, DLSS 3', '<div>La carte graphique Asus GeForce RTX 4060 Ti DUAL OC met à disposition toutes les performances de l\'architecture NVIDIA Ada Lovelace et la puissance combinée du ray-tracing et du DLSS3 pour vous permettre de créer votre configuration PC gamer, qui vous fera vibrer en 1080p dans les meilleures conditions !</div>', '01HNDB4D6JPS6ED7CR7YZC253X.webp', 469.95, 20, 0, '<div><strong>La reine du 1080p<br></strong><br></div><div>L\'objectif premier de cette carte graphique Asus GeForce RTX 4060 Ti DUAL OC, faire de votre configuration PC Gamer la machine idéale pour exceller en 1080p, le tout en profitant du gain de performances et de qualité des technologies DLSS3 et ray-tracing.<br><br></div><div>Ce modèle est prêt à vous épauler avec:<br><br></div><ul><li>Double ventilateurs axiaux pour un refroidissement excellent</li><li>Plaque arrière en aluminium, pour un effet esthétique et dissipateur</li><li>8 Go de mémoire GDDR6 et un total de 4352 coeurs CUDA<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:550,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/ASUS/RTX%204000/800-4060tdual.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/ASUS/RTX%204000/800-4060tdual.jpg" width="800" height="550"><figcaption class="attachment__caption"></figcaption></figure></li></ul><div><strong>Ray-tracing et DLSS3 : toujours plus loin<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, NVIDIA ne se contente pas de proposer un gain de performances conséquent par rapport à la génération précédente, mais également une amélioration des technologies RTX et surtout l\'inauguration du DLSS 3.<br><br></div><div>Optimisé par les coeurs Tensor de 4e génération, le DLSS 3 exploite l\'Intelligence Artificelle pour améliorer les performances graphiques et la résolution de vos jeux grâce à la génération d\'images additionnelles de haute qualité, sans altérer la qualité ni la réactivité.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:460,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg" width="800" height="460"><figcaption class="attachment__caption"></figcaption></figure><br><br></div><div><strong>La plateforme pour les joueurs et les créateurs<br></strong><br></div><div>Et parce que les RTX série 4000 visent également les porteurs de projets créatifs, leurs performances viennent avec leur lot de fonctionnalités :<br><br></div><ul><li>Meilleure réactivité avec la plateforme NVIDIA Reflex</li><li>Conception pour le streaming en direct grâce à l\'encodeur NVIDIA</li><li>Vidéos et voix optimisées par l\'IA via l\'application NVIDIA Broadcast</li><li>Performances et stabilité au rendez-vous avec les pilotes Game ready et Studio<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:450,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg" width="800" height="450"><figcaption class="attachment__caption"></figcaption></figure></li></ul>'),
	(17, 7, 4, 2, '2024-01-30', 'MSI GeForce RTX 4060 GAMING X 8G', 'GeForce RTX 4060, PCI-Express 4.0 8x, 8 Go GDDR6, DLSS 3', '<div>La carte graphique MSI GeForce RTX 4060 GAMING X 8G met à disposition toutes les performances de l\'architecture NVIDIA Ada Lovelace et la puissance combinée du ray-tracing et du DLSS3 pour vous permettre de créer votre configuration PC gamer, qui vous fera vibrer en 1080p dans les meilleures conditions !</div>', '01HNDBJ8SQ15SZ67F1C01204MJ.webp', 409.96, 20, 0, '<div><strong>Pour jouer en Full HD<br></strong><br></div><div>L\'objectif premier de cette carte graphique MSI GeForce RTX 4060 GAMING X 8G, faire de votre configuration PC Gamer la machine idéale pour jouer en 1080p, le tout en profitant du gain de performances et de qualité des technologies DLSS3 et ray-tracing.<br><br></div><div>Ce modèle est prêt à vous épauler avec:<br><br></div><ul><li>Conception thermique TRI Frozr 9 pour une dissipation de la chaleur optimale</li><li>Ventilateurs Torx 5.0, au design pensé pour maintenir un débit d\'air efficace</li><li>Utilitaires MSI Center et Afterburner pour un contrôle précis de vos performances</li><li>8 Go de mémoire GDDR6 et un total de 3072 coeurs CUDA<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:550,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4060gx.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/MSI/RTX%204000/800-4060gx.jpg" width="800" height="550"><figcaption class="attachment__caption"></figcaption></figure></li></ul><div><strong>Ray-tracing et DLSS3 : toujours plus loin<br></strong><br></div><div>Avec l\'architecture Ada Lovelace, NVIDIA ne se contente pas de proposer un gain de performances conséquent par rapport à la génération précédente, mais également une amélioration des technologies RTX et surtout l\'inauguration du DLSS 3.<br><br></div><div>Optimisé par les coeurs Tensor de 4e génération, le DLSS 3 exploite l\'Intelligence Artificelle pour améliorer les performances graphiques et la résolution de vos jeux grâce à la génération d\'images additionnelles de haute qualité, sans altérer la qualité ni la réactivité.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:460,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti-2.jpg" width="800" height="460"><figcaption class="attachment__caption"></figcaption></figure><br><br></div><div><strong>La plateforme pour les joueurs et les créateurs<br></strong><br></div><div>Et parce que les RTX série 4000 visent également les porteurs de projets créatifs, leurs performances viennent avec leur lot de fonctionnalités :<br><br></div><ul><li>Meilleure réactivité avec la plateforme NVIDIA Reflex</li><li>Conception pour le streaming en direct grâce à l\'encodeur NVIDIA</li><li>Vidéos et voix optimisées par l\'IA via l\'application NVIDIA Broadcast</li><li>Performances et stabilité au rendez-vous avec les pilotes Game ready et Studio<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:450,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg&quot;,&quot;width&quot;:800}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/Composants%20PC/Cartes%20graphiques/Nvidia/RTX%204000/800-nv-4060ti.jpg" width="800" height="450"><figcaption class="attachment__caption"></figcaption></figure></li></ul>'),
	(18, 8, 6, 2, '2024-01-30', 'AMD Ryzen 7 5700G', '8 coeurs, 3.80 GHz, 16 Mo, AMD Ryzen, 65 Watts, version boite avec ventilateur', '<div>Le processeur AMD Ryzen 7 5700G offre une base solide pour monter une configuration multimedia et même gaming orientée jeux eSport ! Radeon Vega 8 pour les performances graphiques, architecture Zen 3 gravée en 7 nm pour les applications : AMD propose à nouveau un processeur à l\'excellent rapport prestations-prix !</div>', '01HNDFFHFXS9KPQEFQC6N2BY4J.webp', 239.95, 20, 0, '<div><strong>AMD Ryzen : Architecture Zen 3<br></strong><br></div><div>Profitant d\'une finesse de gravure 7 nanomètres, et de l\'architecture AMD Zen 3, le processeur Ryzen 7 5700G délivre des performances de pointe grâce à ses <strong>8 coeurs</strong> ultra-véloces et ses <strong>16 threads</strong>, ses <strong>16 Mo</strong> de cache et sa fréquence native <strong>3,80 Ghz</strong> (allant jusqu\'à <strong>4,6 GHz</strong> en mode Turbo).<br><br></div><div>Exploitez la puissance hors du commun des processeurs AMD sans sacrifier l\'efficacité énergétique. Véritable prouesse, le processeur AMD <strong>Ryzen 7 5700G</strong> offre des fréquences de fonctionnement élevées pour une consommation électrique mesurée avec une enveloppe thermique (TDP) de seulement <strong>65W</strong>.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:350,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/AR201801170059_d2.jpg&quot;,&quot;width&quot;:400}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/AR201801170059_d2.jpg" width="400" height="350"><figcaption class="attachment__caption"></figcaption></figure></div><div><strong>AMD Radeon Vega Graphics 8<br></strong><br></div><div>Multitache, utilisation intensive, jeux extrême, édition avancée de contenu numérique, Réalité Virtuelle (VR), AMD propose une solution ultra-polyvalente et immédiatement utilisable avec une carte mère socket AM4 (<strong>A520, B550, X570</strong>). Le processeur <strong>AMD Ryzen 7 5700G</strong> possède 8 coeurs Radeon Vega pour une fréquence <strong>2000 MHz</strong>, le tout fusionné en une puce graphique unique permettant d\'offrir toutes les performances dont vous avez besoin pour des tâches exigeantes et de sérieuses possibilités de jeu, et ce, sans faire autant de compromis que cela.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:400,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/vegagraphics.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/amd/vegagraphics.jpg" width="600" height="400"><figcaption class="attachment__caption"></figcaption></figure></div>'),
	(19, 4, 6, 2, '2024-01-30', 'Intel Core i7 12700F', '12 coeurs, 20 threads, 2.1 GHz, 25 Mo, Alder Lake, 65/180 Watts, BX8071512700F', '<div>Nouvel arrivant de la 12ème génération de processeurs Intel Alder Lake, le Intel Core i7-12700F. Grâce à son architecture hybride alliant P-Core et E-Core, et épaulé par le support de la norme PCIe 5.0 et de la mémoire DDR5, il se destine aux configurations PC cherchant des performances optimales. Que ce soit pour du multitâche extrême, de la création de contenu multimedia ou du streaming, il est prêt à répondre à tous vos besoins.</div>', '01HNDFQTQRQQ67MBPYV2H131Z2.webp', 349.96, 20, 0, '<div><strong>Intel 12ème génération : DDR5 et PCIe 5.0<br></strong><br></div><div>Avec Alder Lake, la 12ème génération de processeurs Intel, la marque propose une approche hybride de l\'architecture x86. Jusqu\'alors, Intel incorporait un certain nombre de coeurs dans ses processeurs, chacun identique. Avec Alder Lake, deux types de coeurs sont associés : les Performance-Cores, centrés sur... la performance et utilisés pour les tâches gourmandes en ressources et les Efficient-Cores, plus économes en énergie et pensés pour gérer les tâches de fond. Grâce à cette répartition des tâches, principalement géré via l\'Intel Thread Director, le gain de performances annoncée serait de 19% par rapport à la 11ème génération.<br><br></div><div>Côté technologies, Alder Lake supporte la norme PCIe 5.0 pour la liaison avec la carte graphique (les SSD étant sur des lignes PCIe 4.0) ainsi que la mémoire DDR5, (fréquence de 4800 MHz en natif). La double compatibilité avec la DDR4 est toujours possible (DDR4-3200).<br><br></div><div>Les processeurs Intel Core de 12ème génération sont compatibles uniquement avec les cartes mères des chipsets séries 600. Destiné aux configurations orientées jeu/performances, ce modèle ne dispose pas de contrôleur graphique intégré et nécessite l\'installation d\'une carte graphique dédiée.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-0.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-0.jpg" width="600" height="600"><figcaption class="attachment__caption"></figcaption></figure></div><div><strong>Performances gaming et multitâche<br></strong><br></div><div>Positionné en haut de gamme de l\'architecture <strong>Alder Lake</strong> sur <strong>socket 1700</strong>, le processeur <strong>Core i7 12700</strong> affiche un compteur de 12 coeurs (dont 8 P-cores et 4 E-core) pour 20 Threads ainsi qu\'une fréquence jusqu\'à 4,9 GHz.<br><br></div><div>Pensé pour l\'efficacité, ce processeur se démarque en jeu ou sur de l\'applicatif et du multi-tâche en vous offrant des performances à même de répondre à vos besoins.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:350,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-i7nonK.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/processeurs/12th%20gen/600-i7nonK.jpg" width="600" height="350"><figcaption class="attachment__caption"></figcaption></figure></div>'),
	(20, 5, 7, 2, '2024-01-30', 'be quiet! Pure Base 500DX - Noir', 'Moyenne tour, ATX / Micro ATX / Mini ITX, Noir, ARGB', '<div>Be Quiet décline une nouvelle série de boitiers, le Pure Base 500DX. C\'est un boitier pour PC qui s\'adresse aux gamers, avec un refroidissement performant, un beau panneau latéral en verre trempé et des LEDs RGB en façade pour personnaliser votre PC. Sobre, élégant et conçu avec des matériaux de qualité, il est idéal pour accueillir votre configuration gamer en ATX, Micro ATX et Mini ITX.</div>', '01HNDG0RMVW4GPKVRYTHESCKBD.webp', 119.95, 20, 0, '<div><strong>Le boîtier parfait pour votre PC gamer<br></strong><br></div><div>Le <strong>Be Quiet Pure Base 500DX</strong> est un boîtier PC au format standard moyen tour capable d\'accueillir des cartes mères ATX / Micro ATX ou Mini ITX. Il peut prendre en charge une carte graphique de 369mm, un ventirad de 190mm et une alimentation ATX/EPS de 258mm. Grâce à ces dimensions généreuses : Concevez votre configuration gamer autour de ce beau boîtier qui possède un panneau latéral en verre trempé pour admirer vos composants.<br><br></div><div>Le refroidissement est très important pour un pc de jeu, c\'est pourquoi <strong>Be Quiet</strong> a équipé le <strong>Pure Base 500DX</strong> de 3 ventilateurs PURE WINGS 2 pré-installés. Ce sont des ventilateurs de 140 mm, fixés en façade, à l\'arrière et sur le dessus du boîtier. Le flux d\'air est bien optimisé pour apporter de l\'air frais aux composants et expulser l\'air chaud du PC afin de profiter pleinement de tout son potentiel.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte1.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte1.jpg" width="600" height="600"><figcaption class="attachment__caption"></figcaption></figure></div><div><strong>Des fonctionnalités bien pensées !<br></strong><br></div><div>Chaque gamer a son style, le <strong>Pure Base 500DX</strong> est doté de LEDs ARGB en façade dont les effets sont personnalisables. Vous pouvez aussi synchroniser l\'éclairage avec votre carte mère compatible ou votre contrôleur ARGB pour que tous vos composants s\'illuminent ensemble. En ce qui concerne le stockage, ce boîtier Be Quiet possède 2 baies pour les disques 3.5" HDD et 5 baies au format 2.5" pour vos disques SSD.<br>La connectique en façade est impeccable, avec un port USB 3.1 Type C Gen. 2 permettant l\'utilisation du matériel le plus récent, un connecteur USB 3.0. Il possède également un interrupteur pour contrôler les LED intérieures et extérieures.<br><br></div><div>Si vous souhaitez installés des ventilateurs supplémentaires, le <strong>Pure Base 500DX</strong> peut accueillir jusqu\'à 3 ventilateurs en façade pour perfectionner votre installation. Il peut également prendre en charge un kit de watercooling avec un radiateur compris entre 120 et 240 mm : idéal pour les processeurs haut de gamme.<br>Le système de passe-câbles et rangement de câbles permettent d\'avoir une configuration propre sans fils qui dépassent.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte2.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/boital/bequiet!/purte%20base%20500dx/texte2.jpg" width="600" height="600"><figcaption class="attachment__caption"></figcaption></figure></div>'),
	(21, 9, 7, 2, '2024-01-30', 'Phanteks Enthoo Luxe 2 - Noir', 'Grand Tour, ATX / E-ATX / Micro ATX / Mini ITX, sans alim, Noir, ARGB', '<div>Voici un nouveau boitier Phanteks, le magnifique Enthoo Luxe 2, un écrin au format grande tour capable de prendre en charge ATX / E-ATX / Micro ATX / Mini ITX ou SSI-EEB. Le boitier a été désigné pour accueillir des configurations puissantes, le tout avec une grande flexibilité. Il réserve une grosse capacité de stockage avec jusqu\'à 12 disques durs de 3.5" et 11 disques durs de 2.5". Pour le refroidissement Phanteks offre 15 emplacements de ventilateurs, ce qui est idéal pour un kit watercooling. C\'est un boitier haut de gamme qui saura combler les joueurs les plus exigeants.</div>', '01HNDGDDMJN7G9RRMCZQ3GQC2M.webp', 214.94, 20, 0, '<div><strong>Un boitier de très haute qualité<br></strong><br></div><div><strong>Une personnalisation incroyable<br></strong><br></div><div>Le boitier <strong>Phanteks Enthoo Luxe 2</strong> propose une haute flexibilité dans le montage et le choix de vos composants. En effet, le boitier est au format grand tour, ce qui permet d\'envisager une configuration avec des cartes mères <strong>ATX / E-ATX / Micro ATX / Mini ITX ou SSI-EEB</strong>. Pour votre carte graphique, la limite se situe à 503 mm de long, de quoi faire rentrer l\'intégralité des productions sur le marché à l\'heure actuelle sans aucun problème et une hauteur de ventilateur jusqu\'à 195 mm de haut.<br><br></div><div>On a rarement vu un boitier avec autant de baies de stockage que sur ce <strong>Enthoo Luxe 2</strong>. Phanteks vous réserve une grosse capacité de stockage avec jusqu\'à 12 disques durs de 3.5" et 11 disques durs de 2.5". Cela vous laisse énormément de possbilités d\'installater des tonnes de données pour vos jeux et autres applications.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte1.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte1.jpg" width="600" height="600"><figcaption class="attachment__caption"></figcaption></figure></div><div><strong>Une grand potentiel de refroidissement<br></strong><br></div><div>Conçu principalement pour les joueurs, le <strong>Phanteks Enthoo Luxe 2</strong> là encore tire le meilleur de son très grand gabarit avec un total de 15 emplacements de 120 mm pour installer vos ventilateurs. Grâce à cette multitude d\'emplacement, vous pouvez fixer un kit de watercooling qui possède un radiateur de 120 à 480 mm pour refroidir très efficacement votre processeur Intel ou AMD. Optimisez votre flux d\'air en mettant un maximum de ventilateurs. L\'objectif est vraiment que vos composants restent bien au frais surtout si vous avez une configuration puissante pour en conserver toutes les performances.<br><br></div><div>Le refroidissement est un élément capital pour un PC gaming, et <strong>Phanteks </strong>met tout en oeuvre pour satisfaire les utilisateurs les plus exigeant avec la possibilité d\'installer des ventilateurs 140 mm avec 8 emplacements au total pour varier la disposition de vos ventilateurs. La poussière n\'est pas la bienvenue dans votre installation, et par conséquent 3 filtres à poussière ont été ajoutés à l\'ensemble.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte2.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte2.jpg" width="600" height="600"><figcaption class="attachment__caption"></figcaption></figure></div><div><strong>Un design moderne et élégant<br></strong><br></div><div>Le <strong>Phanteks Enthoo Luxe 2</strong> se situe sur le segment des boitiers PC haut de gamme. Il a été conçu avec des matériaux de grande qualité, de l\'acier, de l\'aluminium et du verre trempé bien épais pour le panneau latéral.<br><br></div><div>Pour personnaliser votre setup le boitier est doté d\'un discret mais joli liseré RGB sur la façade et au niveau du cache de l\'alimentation. De plus, il est même <strong>ARGB </strong>: c\'est-à-dire que vous pouvez synchroniser les effets lumineux avec les systèmes de gestion Asus, MSI, Razer et Aorus. De quoi créer un ensemble unique et totalement original à vôtre image.<br><br></div><div>Au niveau de la connectique, c\'est 1 port <strong>USB 3.1 Gen 2</strong>, <strong>3 ports USB 3.0</strong>, les entrés et orties audio classiques, ainsi que des boutons pour contrôler le rétroéclairage RGB.<figure data-trix-attachment="{&quot;contentType&quot;:&quot;image&quot;,&quot;height&quot;:600,&quot;url&quot;:&quot;https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte3.jpg&quot;,&quot;width&quot;:600}" data-trix-content-type="image" class="attachment attachment--preview"><img src="https://media.materiel.net/bo-images/fiches/composants%20pc/boital/phanteks/enthoo%20luxe%202%20-%20noir/texte3.jpg" width="600" height="600"><figcaption class="attachment__caption"></figcaption></figure></div>');

-- Listage de la structure de table sfecommerce. produit_caracteristique_technique
CREATE TABLE IF NOT EXISTS `produit_caracteristique_technique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produit_id` int NOT NULL,
  `caracteristique_technique_id` int NOT NULL,
  `valeur` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantite` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B8467126F347EFB` (`produit_id`),
  KEY `IDX_B84671266ACEB09F` (`caracteristique_technique_id`),
  CONSTRAINT `FK_B84671266ACEB09F` FOREIGN KEY (`caracteristique_technique_id`) REFERENCES `caracteristique_technique` (`id`),
  CONSTRAINT `FK_B8467126F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.produit_caracteristique_technique : ~37 rows (environ)
INSERT INTO `produit_caracteristique_technique` (`id`, `produit_id`, `caracteristique_technique_id`, `valeur`, `quantite`) VALUES
	(1, 1, 4, 'ATX', NULL),
	(3, 5, 4, 'ATX', NULL),
	(4, 5, 4, 'Micro ATX', NULL),
	(5, 5, 5, '360 mm', NULL),
	(6, 4, 4, 'Micro ATX', NULL),
	(8, 1, 6, 'AMD AM5', NULL),
	(9, 11, 4, 'Micro ATX', NULL),
	(10, 11, 6, 'AMD AM4', NULL),
	(11, 12, 4, 'Micro ATX', NULL),
	(12, 12, 6, 'Intel 1700', NULL),
	(13, 6, 6, 'Intel 1700', NULL),
	(14, 7, 6, 'AMD AM4', NULL),
	(15, 7, 6, 'AMD AM5', NULL),
	(16, 7, 6, 'Intel 1150', NULL),
	(17, 7, 6, 'Intel 1151', NULL),
	(18, 7, 6, 'Intel 1155', NULL),
	(19, 7, 6, 'Intel 1200', NULL),
	(20, 7, 6, 'Intel 1700', NULL),
	(21, 13, 6, 'AMD AM5', NULL),
	(22, 8, 3, 'DDR4 3200 MHz', NULL),
	(23, 8, 7, '2', NULL),
	(24, 1, 8, '4', NULL),
	(25, 1, 3, 'DDR4 3200 MHz', NULL),
	(26, 3, 10, 'PCI Express 4.0 16x', NULL),
	(27, 1, 9, 'PCI Express 4.0 16x', 1),
	(30, 1, 12, 'PCI Express 3.0 1x', 2),
	(32, 3, 14, '192 mm', NULL),
	(33, 5, 15, '7', NULL),
	(34, 1, 16, 'Serial ATA 6Gb/s (SATA Revision 3)', 4),
	(35, 9, 17, 'Serial ATA 6Gb/s (SATA Revision 3)', NULL),
	(36, 10, 18, '+12V (Alimentation P8 - 2 x P4)', 2),
	(37, 10, 18, 'Serial ATA 6Gb/s (SATA Revision 3)', 8),
	(38, 10, 18, 'ATX 20 + 4 Broches', 1),
	(39, 10, 18, 'Disquette Molex 4 Broches Femelle', 1),
	(40, 10, 18, 'Molex (4 broches) Femelle', 1),
	(41, 10, 18, 'PCI Express 6 + 2 Broches', 4),
	(42, 3, 19, 'PCI Express 6 + 2 Broches', NULL),
	(43, 14, 10, 'PCI Express 4.0 16x', NULL),
	(44, 14, 14, '337 mm', NULL),
	(45, 14, 19, 'PCI Express 16 Broches (12VHPWR)', 1),
	(46, 17, 10, 'PCI Express 4.0 16x', NULL),
	(47, 17, 14, '247 mm', NULL),
	(48, 17, 19, 'PCI Express 6 + 2 Broches', 1),
	(49, 16, 10, 'PCI Express 4.0 16x', NULL),
	(50, 16, 14, '227 mm', NULL),
	(51, 16, 19, 'PCI Express 6 + 2 Broches', 1),
	(52, 15, 10, 'PCI Express 3.0 16x', NULL),
	(53, 15, 14, '192 mm', NULL),
	(54, 12, 8, '2', NULL),
	(55, 12, 3, 'DDR4 3200 MHz, DDR4 2933 MHz, DDR4 2666 MHz, DDR4 2400 MHz, DDR4 2133 MHz', NULL),
	(56, 12, 9, 'PCI Express 4.0 16x', 1),
	(57, 12, 12, 'PCI Express 3.0 1x', 1),
	(58, 12, 16, 'Serial ATA 6Gb/s (SATA Revision 3)', 4),
	(59, 12, 16, 'M.2 - PCI-E 3.0 4x + SATA 6 Gb/s', 1),
	(60, 11, 8, '2', NULL),
	(61, 11, 3, 'DDR4 4600 MHz, DDR4 4400 MHz, DDR4 4266 MHz, DDR4 1866 MHz', NULL),
	(62, 11, 9, 'PCI Express 3.0 16x', 1),
	(63, 11, 13, 'PCI Express 3.0 1x', 2),
	(64, 11, 16, 'M.2 - PCI-E 3.0 4x + SATA 6 Gb/s', 1),
	(65, 11, 16, 'Serial ATA 6Gb/s (SATA Revision 3)', 4),
	(66, 18, 6, 'AMD AM4', NULL),
	(67, 19, 6, 'Intel 1700', NULL),
	(68, 20, 4, 'Mini ITX', NULL),
	(69, 20, 4, 'Micro ATX', NULL),
	(70, 20, 4, 'ATX', NULL),
	(71, 20, 5, '369 mm', NULL),
	(72, 20, 15, '5', NULL),
	(73, 21, 4, 'SSI EEB', NULL),
	(74, 21, 4, 'Mini ITX', NULL),
	(75, 21, 4, 'Micro ATX', NULL),
	(76, 21, 4, 'E-ATX', NULL),
	(77, 21, 4, 'ATX', NULL);

-- Listage de la structure de table sfecommerce. produit_commande
CREATE TABLE IF NOT EXISTS `produit_commande` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produit_id` int NOT NULL,
  `commande_id` int NOT NULL,
  `quantite` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_47F5946EF347EFB` (`produit_id`),
  KEY `IDX_47F5946E82EA2E54` (`commande_id`),
  CONSTRAINT `FK_47F5946E82EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  CONSTRAINT `FK_47F5946EF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.produit_commande : ~12 rows (environ)
INSERT INTO `produit_commande` (`id`, `produit_id`, `commande_id`, `quantite`) VALUES
	(11, 3, 8, 2),
	(12, 2, 8, 1),
	(33, 1, 10, 3),
	(34, 2, 11, 1),
	(35, 2, 12, 1),
	(36, 2, 13, 1),
	(37, 2, 14, 1),
	(39, 1, 16, 10),
	(40, 4, 16, 1),
	(42, 1, 15, 11),
	(45, 1, 19, 1),
	(46, 4, 19, 1),
	(124, 8, 20, 1);

-- Listage de la structure de table sfecommerce. produit_config
CREATE TABLE IF NOT EXISTS `produit_config` (
  `id` int NOT NULL AUTO_INCREMENT,
  `produit_id` int NOT NULL,
  `configuration_pc_id` int NOT NULL,
  `quantite` int NOT NULL,
  `etape` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_23D8E446F347EFB` (`produit_id`),
  KEY `IDX_23D8E446116295C8` (`configuration_pc_id`),
  CONSTRAINT `FK_23D8E446116295C8` FOREIGN KEY (`configuration_pc_id`) REFERENCES `configuration_pc` (`id`),
  CONSTRAINT `FK_23D8E446F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.produit_config : ~9 rows (environ)
INSERT INTO `produit_config` (`id`, `produit_id`, `configuration_pc_id`, `quantite`, `etape`) VALUES
	(1, 5, 1, 1, 1),
	(2, 1, 1, 1, 2),
	(3, 7, 1, 1, 4),
	(4, 9, 2, 1, 7),
	(84, 5, 12, 1, 1),
	(85, 5, 13, 1, 1),
	(86, 1, 13, 1, 2),
	(87, 3, 13, 1, 6),
	(88, 9, 13, 1, 7);

-- Listage de la structure de table sfecommerce. utilisateur
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(180) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `civilite` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pseudo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `nom` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1D1C63B3E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Listage des données de la table sfecommerce.utilisateur : ~3 rows (environ)
INSERT INTO `utilisateur` (`id`, `email`, `roles`, `password`, `civilite`, `prenom`, `pseudo`, `is_verified`, `nom`) VALUES
	(1, 'cedric.falda@gmail.com', '["ROLE_ADMIN"]', '$2y$13$SExTMCee1foPum4g0bw1AeN5zrfF0K.HQIfWQD9dMcTv4drdPQMm2', 'monsieur', 'Cédric', 'CFalda', 1, 'FALDA'),
	(5, 'aliev@test.com', '[]', '$2y$13$IjZ5575mCQELUB4qt6WKmuFvC.paZDgeOiuUPu/44PMvXl3UBYJyG', NULL, NULL, NULL, 1, NULL),
	(6, 'test@test.com', '[]', '$2y$13$JlnU.TtOz/3NueDDhHGEyOOozfLDJD/nuYQUznR9KivFyRxjT55IK', NULL, NULL, NULL, 0, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;

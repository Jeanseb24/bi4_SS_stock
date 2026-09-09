-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 09 sep. 2026 à 16:16
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bi4_ss_stock`
--
CREATE DATABASE IF NOT EXISTS `bi4_ss_stock` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `bi4_ss_stock`;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Informatique', 'Ordinateurs, composants et accessoires informatiques.'),
(2, 'Téléphonie', 'Smartphones, montres connectées et accessoires mobiles.'),
(3, 'Audio & Hi-Fi', 'Casques, enceintes sans fil et barres de son.'),
(4, 'Gaming', 'Consoles de jeux, manettes et périphériques gamer.'),
(5, 'Électroménager', 'Appareils pour la cuisine et l\'entretien de la maison.'),
(6, 'Mobilier de Bureau', 'Bureaux ergonomiques, chaises et rangements pro.'),
(7, 'TV & Vidéo', 'Téléviseurs, vidéoprojecteurs et passerelles multimédia.'),
(8, 'Photo & Vidéo', 'Appareils photo numériques, objectifs et trépieds.'),
(9, 'Objets Connectés', 'Domotique, éclairages intelligents et sécurité.'),
(10, 'Accessoires & Câbles', 'Câbles de charge, adaptateurs USB et hubs multifonctions.');

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price` decimal(7,2) NOT NULL,
  `id_category` int NOT NULL,
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_category` (`id_category`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `id_category`, `cover`) VALUES
(1, 'PC Portable Ultra 15', 'Ordinateur portable 15 pouces avec processeur i7 et 16 Go de RAM.', 899.99, 1, '6aa117f1a4e5f-6aa070b32642a-images.jpeg'),
(2, 'Clavier Mécanique RGB', 'Clavier gamer avec interrupteurs tactiles et rétroéclairage personnalisable.', 79.50, 1, '6aa117fbcbad6-6aa070d6695bc-images.jpeg'),
(3, 'Souris Sans Fil Ergonomique', 'Souris sans fil rechargeable adaptée au travail prolongé.', 39.90, 1, '6aa11804a75ba-6aa070efc1fc0-images.jpeg'),
(4, 'Écran 27 Pouces 144Hz', 'Moniteur IPS QHD idéal pour la bureautique et le jeu vidéo.', 249.00, 1, '6aa118e63a6ef-6aa0711591925-images.jpeg'),
(5, 'Smartphone Alpha Pro', 'Smartphone écran OLED 6.5 pouces, 256 Go de stockage et triple capteur photo.', 649.00, 2, '6aa11819dc842-6aa0713195641-sddefault.jpg'),
(6, 'Montre Connectée Sport', 'Montre cardio avec GPS intégré, suivi d\'activité et étanchéité 50m.', 129.99, 2, '6aa11821ab92a-6aa071721855a-picture.jpg'),
(7, 'Chargeur Rapide 65W GaN', 'Chargeur compact avec 2 ports USB-C et 1 port USB-A compatible charge rapide.', 34.90, 2, '6aa1182d0e038-6aa071a0ab810-images.jpeg'),
(8, 'Casque Bluetooth ANC', 'Casque circum-aural avec réduction de bruit active et 30h d\'autonomie.', 149.99, 3, '6aa1188f35871-6aa071c3d4817-images.jpeg'),
(9, 'Enceinte Portable Waterproof', 'Enceinte sans fil résistante à l\'eau IPX7 avec basses renforcées.', 59.95, 3, '6aa1184003670-6aa071da60f9d-images.jpeg'),
(10, 'Écouteurs Sans Fil True Wireless', 'Écouteurs intra-auriculaires avec boîtier de charge compact et réduction de bruit.', 89.00, 3, '6aa11852b42fc-6aa071f4e2473-images.jpeg'),
(11, 'Manette Sans Fil Pro', 'Manette ergonomique compatible PC et consoles de salon.', 54.90, 4, '6aa1185944ac3-6aa0721177e5f-61MalolTdXL.jpg'),
(12, 'Casque Gamer avec Micro', 'Micro-casque audio surround avec coussinets à mémoire de forme.', 69.00, 4, '6aa1189803815-6aa0803ccfa16-images.jpg'),
(13, 'Siège Gamer Ergonomique', 'Fauteuil avec accoudoirs 4D et coussins lombaire et cervical.', 189.90, 4, '6aa118a240f40-6aa0724a8cdba-71Wh0qNRK5L.jpg'),
(14, 'Machine à Café à Grains', 'Cafetière expresso automatique avec broyeur et buse vapeur.', 379.00, 5, '6aa118aabc612-6aa07c4e1a81e-images.jpeg'),
(15, 'Aspirateur Robot Laveur', 'Robot aspirateur avec guidage laser et bac à eau pour lavage des sols.', 299.99, 5, '6aa118b5992ad-6aa07cc97e612-images.jpeg'),
(16, 'Bouilloire Connectée Température Variable', 'Bouilloire inox 1.7L avec sélection précise de température.', 49.99, 5, '6aa118c134b8f-6aa07ce1d9b6e-images.jpeg'),
(17, 'Bureau Électrique Réglable', 'Bureau assis-debout motorisé avec plateau bois 140x70 cm.', 329.00, 6, '6aa118c8a6145-6aa07cf75adf1-images.jpeg'),
(18, 'Support Double Écran', 'Bras articulé pour deux moniteurs jusqu\'à 32 pouces fixation étau.', 45.00, 6, '6aa118d19d6f8-6aa07d36c5835-images.jpeg'),
(19, 'Téléviseur 4K UHD 55 Pouces', 'Smart TV LED 4K avec HDR10+ et assistants vocaux intégrés.', 499.00, 7, '6aa118dd8dac4-6aa07d92140e4-images.jpeg'),
(20, 'Passerelle Multimédia 4K', 'Lecteur streaming HDMI avec télécommande vocale et Wi-Fi 6.', 64.99, 7, '6aa118f15e377-6aa07dd7b0e5c-images.jpeg'),
(21, 'Appareil Photo Hybride 24MP', 'Boîtier numérique avec capteur APS-C et enregistrement vidéo 4K.', 749.00, 8, '6aa119016d5cc-6aa07e04c1c99-images.jpeg'),
(22, 'Trépied Vidéo Aluminium', 'Trépied polyvalent avec tête fluide pour prise de vue stable.', 79.00, 8, '6aa11909b44d3-6aa07e247205a-images.jpeg'),
(23, 'Ampoule Connectée RGB E27', 'Ampoule LED connectée Wi-Fi sans pont requis, intensité variable.', 14.90, 9, '6aa1191462603-6aa07e5080191-images.jpeg'),
(24, 'Caméra de Sécurité Intérieure 2K', 'Caméra motorisée 360 degrés avec vision nocturne et détection de mouvement.', 39.99, 9, '6aa1191f8d2a8-6aa07e66192f4-images.jpeg'),
(25, 'Hub USB-C 8-en-1', 'Adaptateur avec port HDMI 4K, lecteur SD, ports USB 3.0 et port réseau RJ45.', 29.99, 10, '6aa119286f3cd-6aa07e7ac3a39-images.jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `login`, `email`, `password`) VALUES
(1, 'Admin', 'admin@myepse.be', '$2y$10$iXFJqoA40mxHcr.mwYBQ/uMz3mvUYrVEtdmpQCW.2GhNcCqPkBkOi');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

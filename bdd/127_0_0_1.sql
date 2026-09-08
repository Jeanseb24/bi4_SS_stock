-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 08 sep. 2026 à 21:35
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
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Cat_1', 'Description de la première catégorie'),
(4, 'Cat_2', 'Description de la deuxième catégorie'),
(5, 'Cat_3', 'Description de la catégorie 3'),
(6, 'Cat_4', 'Description de la 4ème catégorie'),
(7, 'Informatique', 'Ordinateurs, composants et accessoires informatiques.'),
(8, 'Téléphonie', 'Smartphones, montres connectées et accessoires mobiles.'),
(9, 'Audio & Hi-Fi', 'Casques, enceintes sans fil et barres de son.'),
(10, 'Gaming', 'Consoles de jeux, manettes et périphériques gamer.'),
(11, 'Électroménager', 'Appareils pour la cuisine et l\'entretien de la maison.'),
(12, 'Mobilier de Bureau', 'Bureaux ergonomiques, chaises et rangements pro.'),
(13, 'TV & Vidéo', 'Téléviseurs, vidéoprojecteurs et passerelles multimédia.'),
(14, 'Photo & Vidéo', 'Appareils photo numériques, objectifs et trépieds.'),
(15, 'Objets Connectés', 'Domotique, éclairages intelligents et sécurité.'),
(16, 'Accessoires & Câbles', 'Câbles de charge, adaptateurs USB et hubs multifonctions.');

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
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `id_category`, `cover`) VALUES
(2, 'produit2', 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).', 22.00, 1, '6aa0465805092-pexels-martijnadegeest-633565.jpg'),
(3, 'produit3', 'It is a long established fact that a reader will b...', 32.00, 4, '6aa0461ea98a1-81RCr4N-RyL.jpg'),
(9, 'test9', 'test', 125.00, 5, '6aa02bd680612-6a9eb1394d5cf-images.jpg'),
(10, 'Test Cat2', 'Test cat2', 15.00, 5, '6aa069a9e3a6d-o-3b0063b653ac86dd31f5ebd718194716-bmw-2-f22-f23-tail-light-part.jpg'),
(11, 'Test Cat4', 'Catégorie 4 en test', 150.00, 6, '6aa069ec19573-u9h8bsny6mv9tft01zqtrxfjj41gxo28.jpg'),
(12, 'PC Portable Ultra 15', 'Ordinateur portable 15 pouces avec processeur i7 et 16 Go de RAM.', 899.99, 7, '6aa070b32642a-images.jpeg'),
(13, 'Clavier Mécanique RGB', 'Clavier gamer avec interrupteurs tactiles et rétroéclairage personnalisable.', 79.50, 7, '6aa070d6695bc-images.jpeg'),
(14, 'Souris Sans Fil Ergonomique', 'Souris sans fil rechargeable adaptée au travail prolongé.', 39.90, 7, '6aa070efc1fc0-images.jpeg'),
(15, 'Écran 27 Pouces 144Hz', 'Moniteur IPS QHD idéal pour la bureautique et le jeu vidéo.', 249.00, 7, '6aa0711591925-images.jpeg'),
(16, 'Smartphone Alpha Pro', 'Smartphone écran OLED 6.5 pouces, 256 Go de stockage et triple capteur photo.', 649.00, 8, '6aa0713195641-sddefault.jpg'),
(17, 'Montre Connectée Sport', 'Montre cardio avec GPS intégré, suivi d\'activité et étanchéité 50m.', 129.99, 8, '6aa071721855a-picture.jpg'),
(18, 'Chargeur Rapide 65W GaN', 'Chargeur compact avec 2 ports USB-C et 1 port USB-A compatible charge rapide.', 34.90, 8, '6aa071a0ab810-images.jpeg'),
(19, 'Casque Bluetooth ANC', 'Casque circum-aural avec réduction de bruit active et 30h d\'autonomie.', 149.99, 9, '6aa071c3d4817-images.jpeg'),
(20, 'Enceinte Portable Waterproof', 'Enceinte sans fil résistante à l\'eau IPX7 avec basses renforcées.', 59.95, 9, '6aa071da60f9d-images.jpeg'),
(21, 'Écouteurs Sans Fil True Wireless', 'Écouteurs intra-auriculaires avec boîtier de charge compact et réduction de bruit.', 89.00, 9, '6aa071f4e2473-images.jpeg'),
(22, 'Manette Sans Fil Pro', 'Manette ergonomique compatible PC et consoles de salon.', 54.90, 10, '6aa0721177e5f-61MalolTdXL.jpg'),
(23, 'Casque Gamer avec Micro', 'Micro-casque audio surround avec coussinets à mémoire de forme.', 69.00, 10, '6aa0722b0a146-micro-casque-lumineux-usb-special-gaming-ghs-400-led-son-surround-7-1-ref-ZX1662-3.jpg'),
(24, 'Siège Gamer Ergonomique', 'Fauteuil avec accoudoirs 4D et coussins lombaire et cervical.', 189.90, 10, '6aa0724a8cdba-71Wh0qNRK5L.jpg'),
(25, 'Machine à Café à Grains', 'Cafetière expresso automatique avec broyeur et buse vapeur.', 379.00, 11, '6aa07c4e1a81e-images.jpeg'),
(26, 'Aspirateur Robot Laveur', 'Robot aspirateur avec guidage laser et bac à eau pour lavage des sols.', 299.99, 11, '6aa07cc97e612-images.jpeg'),
(27, 'Bouilloire Connectée Température Variable', 'Bouilloire inox 1.7L avec sélection précise de température.', 49.99, 11, '6aa07ce1d9b6e-images.jpeg'),
(28, 'Bureau Électrique Réglable', 'Bureau assis-debout motorisé avec plateau bois 140x70 cm.', 329.00, 12, '6aa07cf75adf1-images.jpeg'),
(29, 'Support Double Écran', 'Bras articulé pour deux moniteurs jusqu\'à 32 pouces fixation étau.', 45.00, 12, '6aa07d36c5835-images.jpeg'),
(30, 'Téléviseur 4K UHD 55 Pouces', 'Smart TV LED 4K avec HDR10+ et assistants vocaux intégrés.', 499.00, 13, '6aa07d92140e4-images.jpeg'),
(31, 'Passerelle Multimédia 4K', 'Lecteur streaming HDMI avec télécommande vocale et Wi-Fi 6.', 64.99, 13, '6aa07dd7b0e5c-images.jpeg'),
(32, 'Appareil Photo Hybride 24MP', 'Boîtier numérique avec capteur APS-C et enregistrement vidéo 4K.', 749.00, 14, '6aa07e04c1c99-images.jpeg'),
(33, 'Trépied Vidéo Aluminium', 'Trépied polyvalent avec tête fluide pour prise de vue stable.', 79.00, 14, '6aa07e247205a-images.jpeg'),
(34, 'Ampoule Connectée RGB E27', 'Ampoule LED connectée Wi-Fi sans pont requis, intensité variable.', 14.90, 15, '6aa07e5080191-images.jpeg'),
(35, 'Caméra de Sécurité Intérieure 2K', 'Caméra motorisée 360 degrés avec vision nocturne et détection de mouvement.', 39.99, 15, '6aa07e66192f4-images.jpeg'),
(36, 'Hub USB-C 8-en-1', 'Adaptateur avec port HDMI 4K, lecteur SD, ports USB 3.0 et port réseau RJ45.', 29.99, 16, '6aa07e7ac3a39-images.jpeg');

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
(1, 'Mjseb', 'admin@myepse.be', '$argon2id$v=19$m=65536,t=4,p=1$QjAzYi9wMzBObFRwLlZ4TQ$NosnbWoRKXO7NmllMvDCg0+XczmSUPkaHQRzN54oV48');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

create database gaming;

use gaming;
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2024 at 11:32 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gaming_store`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ShowInsertions` ()   BEGIN
    -- Afficher les insertions de la table categories
    SELECT CONCAT('INSERT INTO `categories` (Id_Cat, Name_Cat) VALUES (', Id_Cat, ', "', Name_Cat, '");') AS InsertStatement
    FROM categories;

    -- Afficher les insertions de la table favorites
    SELECT CONCAT('INSERT INTO `favorites` (Id_Fav, Id_P, Id_user) VALUES (', Id_Fav, ', ', Id_P, ', ', Id_user, ');') AS InsertStatement
    FROM favorites;

    -- Afficher les insertions de la table produits
    SELECT CONCAT('INSERT INTO `produits` (Id_P, Name_P, Desc_P, Prix_P, Date_R, Img_P, Id_Cat) VALUES (', Id_P, ', "', Name_P, '", "', Desc_P, '", ', Prix_P, ', "', Date_R, '", "', Img_P, '", ', Id_Cat, ');') AS InsertStatement
    FROM produits;

    -- Afficher les insertions de la table users
    SELECT CONCAT('INSERT INTO `users` (Id_user, UserName, Password, email, role) VALUES (', Id_user, ', "', UserName, '", "', Password, '", "', email, '", "', role, '");') AS InsertStatement
    FROM users;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `Id_Cat` int(11) NOT NULL,
  `Name_Cat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`Id_Cat`, `Name_Cat`) VALUES
(1, 'Consoles de jeux'),
(2, 'Jeux vidéo'),
(3, 'Accessoires de jeux'),
(4, 'PC Gaming'),
(5, 'Matériel de streaming'),
(6, 'Vêtements de gamer'),
(7, 'Figurines et collectibles'),
(8, 'Équipement de compétition'),
(9, 'Sons et casques'),
(10, 'Rétrogaming');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `Id_Fav` int(11) NOT NULL,
  `Id_P` int(11) NOT NULL,
  `Id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`Id_Fav`, `Id_P`, `Id_user`) VALUES
(13, 5, 12),
(14, 1, 16),
(15, 2, 16),
(19, 2, 12),
(21, 6, 12);

-- --------------------------------------------------------

--
-- Table structure for table `produits`
--

CREATE TABLE `produits` (
  `Id_P` int(11) NOT NULL,
  `Name_P` varchar(100) NOT NULL,
  `Desc_P` text NOT NULL,
  `Prix_P` decimal(10,2) NOT NULL,
  `Date_R` date NOT NULL,
  `Img_P` varchar(255) NOT NULL,
  `Id_Cat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produits`
--

INSERT INTO `produits` (`Id_P`, `Name_P`, `Desc_P`, `Prix_P`, `Date_R`, `Img_P`, `Id_Cat`) VALUES
(1, 'PlayStation 5 +Gift CD', 'Console de jeu dernière génération de Sony.', 2400.00, '2024-01-15', 'img/ps5.jpg', 1),
(2, 'Xbox Series X', 'Console de jeu de Microsoft avec une puissance incroyable.', 1900.00, '2024-01-20', 'img/xbox.jpg', 1),
(4, 'Razer BlackWidow V3', 'Clavier mécanique RGB pour gamers.', 139.99, '2024-01-25', 'img/keyboard.jpg', 3),
(5, 'Logitech G502', 'Souris de jeu ultra-précise.', 79.99, '2024-01-30', 'img/mouse.jpg', 3),
(6, 'Casque HyperX Cloud II', 'Casque de jeu avec son surround.', 89.99, '2024-01-15', 'img/headset.jpg', 9),
(7, 'ASUS ROG Strix Scar 15', 'Ordinateur portable gaming haute performance.', 1999.99, '2024-03-10', 'img/laptop.jpg', 4),
(8, 'T-shirt Gamer', 'T-shirt en coton avec logo gaming.', 24.99, '2024-02-10', 'img/tshirt_gamer.jpg', 6),
(9, 'Figurine Funko Pop - Mario', 'Figurine de collection de Mario.', 14.99, '2024-01-05', 'img/mario.jpg', 7),
(10, 'Moniteur ASUS ROG Swift', 'Moniteur gaming 144Hz pour une expérience fluide.', 399.99, '2024-02-15', 'img/monitor.jpg', 4),
(11, 'PlayStation 4', 'La PlayStation 4 (PS4) est une console de jeu vidéo dotée d\'un processeur à huit cœurs, d\'une RAM de 8 Go, d\'un stockage allant jusqu\'à 1 To, et d\'une capacité de jeu en 1080p avec un large éventail de titres exclusifs.', 800.00, '0000-00-00', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/PS4-Console-wDS4.jpg/1280px-PS4-Console-wDS4.jpg', 1),
(13, 'Manette PS5 Sans Fil Dualsense', 'Découvrez une expérience de gaming plus intense qui porte l’action au creux de vos mains, à un prix à peine croyable en Tunisie, couleur bleu cobalt. La manette sans fil DualSense offre un immersif retour haptique, des gâchettes adaptatives et un microphone intégré, tout cela dans un design plus confortable que jamais. Profitez d\\\'un retour tactile lors de vos actions dans les jeux grâce à deux mécanismes qui remplacent les moteurs de vibration traditionnels. Dans vos mains, ces vibrations dynamiques peuvent simuler des interactions avec l\\\'environnement ainsi que le recul de diverses armes.', 100.00, '0000-00-00', 'img/manette ps5.jpg', 1),
(37, 'Call of Duty: Modern Warfare II', 'Le dernier opus de la série Call of Duty avec un gameplay intense pour PS4', 69.99, '2024-01-15', 'https://i5.walmartimages.com/seo/Call-of-Duty-Modern-Warfare-II-C-O-D-E-Edition-PlayStation-4_afc46290-c29d-4b46-ac5c-78dab8099b5b.67a499126f0fd4c8b049c9a86bce6643.jpeg', 1),
(38, 'FIFA 24', 'Le jeu de football le plus réaliste avec les dernières équipes et joueurs for XBOX', 59.99, '2024-02-20', 'https://i5.walmartimages.com/seo/EA-Sports-FC-24-Xbox-Series-X_4e615c90-3c1b-468c-8d30-640ee9bc1449.7ec60e2bc07c455b98548806b8dab05c.webp', 2),
(39, 'The Legend of Zelda: Tears of the Kingdom', 'Une aventure épique dans le royaume d’Hyrule pour SWITCH', 64.99, '2024-03-05', 'https://m.media-amazon.com/images/I/81eHh0BNU0L.jpg', 3),
(40, 'Assassin’s Creed Mirage', 'Explorez un monde ouvert à travers l’histoire fascinante des assassins.', 59.99, '2024-04-10', 'https://enderg.com/wp-content/uploads/2023/10/OIP.jpeg', 4),
(41, 'Elden Ring', 'Un RPG d’action avec un monde vaste et des combats exigeants.', 69.99, '2024-05-25', 'https://p325k7wa.twic.pics/high/elden-ring/elden-ring/08-shadow-of-the-erdtree/elden-ring-expansion-SOTE/00-page-content/ERSOTE-header-mobile.jpg?twic=v1/step=10/quality=80/max=760', 5),
(42, 'God of War: Ragnarök', 'Une suite épique avec des graphismes époustouflants et un gameplay captivant pour PS5', 64.99, '2024-06-30', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRliRCFF8oYad8Ow-7VYnYJ_KmrRWDJlQyt4A&s', 2),
(43, 'Gran Turismo 7', 'Le jeu de course ultime avec une simulation réaliste.', 59.99, '2024-07-15', 'https://hnau.imgix.net/media/catalog/product/g/r/gran_turismo_7_ps5_1__3.jpg?auto=compress&auto=format&fill-color=FFFFFF&fit=fill&fill=solid&w=496&h=279', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id_user` int(11) NOT NULL,
  `UserName` varchar(50) DEFAULT NULL,
  `Password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id_user`, `UserName`, `Password`, `email`, `role`) VALUES
(1, 'manel', 'manel', 'manel@gmail.com', 'admin'),
(2, 'yosr', 'yosr', 'yosr@gmail.com', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`Id_Cat`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`Id_Fav`),
  ADD KEY `Id_P` (`Id_P`),
  ADD KEY `Id_user` (`Id_user`);

--
-- Indexes for table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`Id_P`),
  ADD KEY `Id_Cat` (`Id_Cat`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `Id_Cat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `Id_Fav` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `produits`
--
ALTER TABLE `produits`
  MODIFY `Id_P` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`Id_user`) REFERENCES `users` (`Id_user`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`Id_P`) REFERENCES `produits` (`Id_P`);

--
-- Constraints for table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`Id_Cat`) REFERENCES `categories` (`Id_Cat`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

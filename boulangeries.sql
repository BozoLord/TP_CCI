-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Jeu 04 Janvier 2018 à 17:31
-- Version du serveur :  10.1.26-MariaDB-0+deb9u1
-- Version de PHP :  7.0.16-3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `boulangeries`
--

-- --------------------------------------------------------

--
-- Structure de la table `Magasins`
--

CREATE TABLE `Magasins` (
  `ID_Magasin` int(11) NOT NULL,
  `Adresse` text NOT NULL,
  `Enseigne` text NOT NULL,
  `FK_ID_Ville` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Magasins`
--

INSERT INTO `Magasins` (`ID_Magasin`, `Adresse`, `Enseigne`, `FK_ID_Ville`) VALUES
(1, '4 place du général de Gaulle', 'Boulangerie-ange', 1),
(2, '16 rue Jules Guesde', 'Paul', 2),
(3, '24 Avenue Dampierre', 'Paul', 2),
(4, '8bis route de Douai', 'Au pain campagnard', 1),
(5, '1233 Impasse Gustave Delory', 'Les blés d\'or', 3),
(6, '8 avenue de la Joconde', 'Au charlot gourmand', 4),
(7, '14 rue du Cinema Max', 'Louise', 2),
(8, '116 Route de Milona', 'Grenier à blés', 3),
(9, '54 rue Pierre Dubois', 'Boulangerie-ange', 2),
(10, '78 route de la liberté', 'Paul', 1),
(11, '1 avenue de l’impasse Léon', 'Paul', 1),
(12, '88 place de  Marque Lavoine', 'Au pain campagnard', 3),
(13, '16 Ombre de l’âme', 'Les blés d\'or', 4),
(14, '78 rue de Somain', 'Les blés d\'or', 4),
(15, '25 place de Jacques Chirac', 'Au charlot gourmand', 1),
(16, '210bis Paul Lavue', 'Louise', 3),
(17, '5 route de la défense', 'Grenier à blés', 4);

-- --------------------------------------------------------

--
-- Structure de la table `Produits`
--

CREATE TABLE `Produits` (
  `ID_Produit` int(11) NOT NULL,
  `Nom` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Produits`
--

INSERT INTO `Produits` (`ID_Produit`, `Nom`) VALUES
(1, 'Tarte aux fraises'),
(2, 'Tarte aux pommes'),
(3, 'Tarte multi-fruits'),
(4, 'Tarte aux framboises'),
(5, 'Tarte aux citron meringuée'),
(6, 'Tarte au chocolat'),
(7, 'Tartelettes'),
(8, 'Eclairs'),
(9, 'Mille feuille'),
(10, 'Brownies'),
(11, 'Cakes'),
(12, 'Croissant'),
(13, 'Pain chocolat'),
(14, 'Pain aux raisins'),
(15, 'Chausson aux pommes'),
(16, 'Beignets'),
(17, 'Tarte aux abricots');

-- --------------------------------------------------------

--
-- Structure de la table `Produit_Magasin`
--

CREATE TABLE `Produit_Magasin` (
  `Prix` double NOT NULL,
  `FK_ID_Magasin` int(11) NOT NULL,
  `FK_ID_Produit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Produit_Magasin`
--

INSERT INTO `Produit_Magasin` (`Prix`, `FK_ID_Magasin`, `FK_ID_Produit`) VALUES
(2.3, 1, 16),
(1.5, 1, 15),
(1.2, 1, 14),
(0.8, 1, 13),
(1.1, 1, 12),
(3, 1, 11),
(4.32, 1, 10),
(2, 1, 9),
(2, 1, 8),
(5.9, 1, 7),
(12, 1, 6),
(14, 1, 5),
(12, 1, 4),
(12, 1, 17),
(12, 1, 3),
(12, 1, 2),
(12, 1, 1),
(2, 2, 9),
(2, 2, 8),
(5.9, 2, 7),
(12, 2, 6),
(14, 2, 5),
(14.3, 2, 4),
(12, 2, 17),
(11, 2, 3),
(12, 2, 2),
(12, 2, 1),
(2.3, 3, 16),
(1.6, 3, 15),
(1.2, 3, 14),
(0.8, 3, 13),
(1.15, 3, 12),
(3, 3, 11),
(4.32, 3, 10),
(2.3, 4, 16),
(1.6, 4, 15),
(1.2, 4, 14),
(0.8, 4, 13),
(1.15, 4, 12),
(3, 4, 11),
(4.32, 4, 10),
(2.1, 4, 9),
(2, 4, 8),
(5.9, 4, 7),
(12, 4, 6),
(21, 4, 5),
(12, 4, 4),
(15, 4, 17),
(12, 4, 3),
(12, 4, 2),
(13, 4, 1),
(2, 5, 16),
(1.6, 5, 15),
(1.1, 5, 14),
(0.8, 5, 13),
(1.15, 5, 12),
(3, 5, 11),
(4.32, 5, 10),
(2.1, 5, 9),
(1.95, 5, 8),
(5.3, 5, 7),
(12, 5, 6),
(14, 5, 5),
(12, 5, 4),
(15, 5, 17),
(12, 5, 3),
(12, 5, 2),
(12.1, 5, 1),
(2.15, 6, 16),
(2.1, 6, 15),
(2.1, 6, 14),
(2, 6, 13),
(1.6, 6, 12),
(1.1, 6, 10),
(0.8, 6, 9),
(1.15, 6, 8),
(6.13, 6, 7),
(14, 6, 6),
(11.2, 6, 5),
(11.2, 6, 4),
(11.2, 6, 17),
(2, 7, 10),
(3, 7, 9),
(2, 7, 8),
(2.1, 7, 7),
(10, 7, 6),
(11, 7, 5),
(12, 7, 4),
(13, 7, 17),
(14, 7, 3),
(15, 7, 2),
(16, 7, 1),
(2.05, 8, 14),
(2, 8, 13),
(1, 8, 12),
(2, 8, 11),
(2.1, 8, 10),
(10, 8, 9),
(11, 8, 8),
(12, 8, 7),
(13, 8, 6),
(14, 8, 5),
(15, 8, 4),
(16, 8, 17),
(2.3, 9, 16),
(1.5, 9, 15),
(1.2, 9, 14),
(0.8, 9, 13),
(1.1, 9, 12),
(3, 9, 11),
(4.32, 9, 10),
(2, 9, 9),
(2, 9, 8),
(5.9, 9, 7),
(12, 9, 6),
(14, 9, 5),
(12, 9, 4),
(12, 9, 17),
(12, 9, 3),
(12, 9, 2),
(12, 9, 1),
(2, 10, 8),
(5.9, 10, 7),
(12, 10, 6),
(14, 10, 5),
(14.3, 10, 4),
(12, 10, 17),
(11, 10, 3),
(12, 10, 2),
(12, 10, 1),
(2.3, 11, 16),
(1.6, 11, 15),
(1.2, 11, 14),
(0.8, 11, 13),
(1.15, 11, 12),
(3, 11, 11),
(4.32, 11, 10),
(2, 11, 9),
(2.35, 12, 16),
(1.6, 12, 15),
(1.22, 12, 14),
(0.8, 12, 13),
(1.15, 12, 12),
(3.1, 12, 11),
(4.53, 12, 10),
(2.1, 12, 9),
(2, 12, 8),
(5.9, 12, 7),
(12, 12, 6),
(12, 12, 5),
(12, 12, 4),
(15, 12, 17),
(11, 12, 3),
(12.5, 12, 2),
(13, 12, 1),
(14, 13, 5),
(12, 13, 4),
(15, 13, 17),
(12, 13, 3),
(12, 13, 2),
(12.1, 13, 1),
(2, 14, 16),
(1.75, 14, 15),
(1.1, 14, 14),
(0.8, 14, 13),
(1.22, 14, 12),
(3, 14, 11),
(4.32, 14, 10),
(2.1, 14, 9),
(1.95, 14, 8),
(5.3, 14, 7),
(12, 14, 6),
(1.75, 15, 16),
(1.1, 15, 15),
(0.9, 15, 14),
(1.22, 15, 13),
(3, 15, 12),
(4.32, 15, 10),
(2.1, 15, 9),
(1.95, 15, 8),
(5.3, 15, 7),
(12, 15, 6),
(14, 15, 5),
(11.9, 15, 4),
(15, 15, 17),
(1.22, 16, 10),
(3, 16, 9),
(4.32, 16, 8),
(2.1, 16, 7),
(7.6, 16, 6),
(5.3, 16, 5),
(12, 16, 4),
(14, 16, 17),
(11.9, 16, 3),
(15, 16, 2),
(5, 16, 1),
(1.5, 17, 14),
(1.5, 17, 13),
(1.5, 17, 12),
(1.5, 17, 11),
(1.5, 17, 10),
(1.5, 17, 9),
(1.5, 17, 8),
(3.5, 17, 7),
(5, 17, 6),
(10.56, 17, 5),
(14, 17, 4),
(18, 17, 17);

-- --------------------------------------------------------

--
-- Structure de la table `Villes`
--

CREATE TABLE `Villes` (
  `ID_Ville` int(11) NOT NULL,
  `Nom` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contenu de la table `Villes`
--

INSERT INTO `Villes` (`ID_Ville`, `Nom`) VALUES
(1, 'Valenciennes'),
(2, 'Lille'),
(3, 'Arras'),
(4, 'Cambrai');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Magasins`
--
ALTER TABLE `Magasins`
  ADD PRIMARY KEY (`ID_Magasin`);

--
-- Index pour la table `Produits`
--
ALTER TABLE `Produits`
  ADD PRIMARY KEY (`ID_Produit`);

--
-- Index pour la table `Villes`
--
ALTER TABLE `Villes`
  ADD PRIMARY KEY (`ID_Ville`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Magasins`
--
ALTER TABLE `Magasins`
  MODIFY `ID_Magasin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `Produits`
--
ALTER TABLE `Produits`
  MODIFY `ID_Produit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT pour la table `Villes`
--
ALTER TABLE `Villes`
  MODIFY `ID_Ville` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

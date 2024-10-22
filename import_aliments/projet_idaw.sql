-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 21 oct. 2024 à 15:10
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `projet_idaw`
--

-- --------------------------------------------------------

--
-- Structure de la table `aliment`
--

DROP TABLE IF EXISTS `aliment`;
CREATE TABLE IF NOT EXISTS `aliment` (
  `NOM_ALIMENT` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `CODE_TYPE` int NOT NULL,
  PRIMARY KEY (`NOM_ALIMENT`),
  KEY `FK_ASSOCIATION_6` (`CODE_TYPE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `aliment`
--


-- --------------------------------------------------------

--
-- Structure de la table `compose`
--

DROP TABLE IF EXISTS `compose`;
CREATE TABLE IF NOT EXISTS `compose` (
  `ALI_NOM_ALIMENT` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `NOM_ALIMENT` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `QUANTITE_ALIMENT` decimal(15,0) NOT NULL,
  PRIMARY KEY (`ALI_NOM_ALIMENT`,`NOM_ALIMENT`),
  KEY `FK_COMPOSE2` (`NOM_ALIMENT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contient`
--

DROP TABLE IF EXISTS `contient`;
CREATE TABLE IF NOT EXISTS `contient` (
  `NOM_ALIMENT` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `CODE_REPAS` int NOT NULL,
  `QUANTITE` decimal(15,0) NOT NULL,
  PRIMARY KEY (`NOM_ALIMENT`,`CODE_REPAS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contient_ratio`
--

DROP TABLE IF EXISTS `contient_ratio`;
CREATE TABLE IF NOT EXISTS `contient_ratio` (
  `NOM_ALIMENT` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `CODE_RATIO` int NOT NULL,
  `QUANTITE_RATIO` double DEFAULT NULL,
  PRIMARY KEY (`NOM_ALIMENT`,`CODE_RATIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pratique_sport`
--

DROP TABLE IF EXISTS `pratique_sport`;
CREATE TABLE IF NOT EXISTS `pratique_sport` (
  `CODE_SPORT` int NOT NULL AUTO_INCREMENT,
  `NOM_SPORT` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CODE_SPORT`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `pratique_sport`
--

INSERT INTO `pratique_sport` (`CODE_SPORT`, `NOM_SPORT`) VALUES
(1, 'extreme'),
(2, 'regulier'),
(3, 'faible'),
(4, 'nulle');

-- --------------------------------------------------------

--
-- Structure de la table `ratio`
--

DROP TABLE IF EXISTS `ratio`;
CREATE TABLE IF NOT EXISTS `ratio` (
  `CODE_RATIO` int NOT NULL AUTO_INCREMENT,
  `NOM_RATIO` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CODE_RATIO`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ratio`
--

-- --------------------------------------------------------

--
-- Structure de la table `repas`
--

DROP TABLE IF EXISTS `repas`;
CREATE TABLE IF NOT EXISTS `repas` (
  `CODE_REPAS` int NOT NULL AUTO_INCREMENT,
  `LOGIN` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `DATE` datetime NOT NULL,
  PRIMARY KEY (`CODE_REPAS`),
  KEY `FK_MANGE` (`LOGIN`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `repas`
--


-- --------------------------------------------------------

--
-- Structure de la table `sexe`
--

DROP TABLE IF EXISTS `sexe`;
CREATE TABLE IF NOT EXISTS `sexe` (
  `CODE_SEXE` int NOT NULL AUTO_INCREMENT,
  `NOM_SEXE` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CODE_SEXE`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sexe`
--

INSERT INTO `sexe` (`CODE_SEXE`, `NOM_SEXE`) VALUES
(1, 'masculin'),
(2, 'feminin');

-- --------------------------------------------------------

--
-- Structure de la table `tranche_age`
--

DROP TABLE IF EXISTS `tranche_age`;
CREATE TABLE IF NOT EXISTS `tranche_age` (
  `CODE_AGE` int NOT NULL AUTO_INCREMENT,
  `TRANCHE` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CODE_AGE`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tranche_age`
--

INSERT INTO `tranche_age` (`CODE_AGE`, `TRANCHE`) VALUES
(1, 'enfant'),
(2, 'jeune_adulte'),
(3, 'adulte'),
(4, 'retraite');

-- --------------------------------------------------------

--
-- Structure de la table `type_aliment`
--

DROP TABLE IF EXISTS `type_aliment`;
CREATE TABLE IF NOT EXISTS `type_aliment` (
  `CODE_TYPE` int NOT NULL AUTO_INCREMENT,
  `NOM_TYPE` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`CODE_TYPE`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `type_aliment`
--

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `LOGIN` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `CODE_AGE` int NOT NULL,
  `CODE_SEXE` int NOT NULL,
  `CODE_SPORT` int NOT NULL,
  `MDP` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `NOM` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `PRENOM` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `DATE_NAISSANCE` date NOT NULL,
  `EMAIL` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`LOGIN`),
  KEY `FK_ASSOCIATION_1` (`CODE_AGE`),
  KEY `FK_ASSOCIATION_2` (`CODE_SEXE`),
  KEY `FK_ASSOCIATION_3` (`CODE_SPORT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--


--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `aliment`
--
ALTER TABLE `aliment`
  ADD CONSTRAINT `FK_ASSOCIATION_6` FOREIGN KEY (`CODE_TYPE`) REFERENCES `type_aliment` (`CODE_TYPE`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `compose`
--
ALTER TABLE `compose`
  ADD CONSTRAINT `FK_COMPOSE` FOREIGN KEY (`ALI_NOM_ALIMENT`) REFERENCES `aliment` (`NOM_ALIMENT`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_COMPOSE2` FOREIGN KEY (`NOM_ALIMENT`) REFERENCES `aliment` (`NOM_ALIMENT`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `contient`
--
ALTER TABLE `contient`
  ADD CONSTRAINT `FK_CONTIENT` FOREIGN KEY (`NOM_ALIMENT`) REFERENCES `aliment` (`NOM_ALIMENT`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `repas`
--
ALTER TABLE `repas`
  ADD CONSTRAINT `FK_MANGE` FOREIGN KEY (`LOGIN`) REFERENCES `utilisateur` (`LOGIN`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_ASSOCIATION_1` FOREIGN KEY (`CODE_AGE`) REFERENCES `tranche_age` (`CODE_AGE`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_ASSOCIATION_2` FOREIGN KEY (`CODE_SEXE`) REFERENCES `sexe` (`CODE_SEXE`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `FK_ASSOCIATION_3` FOREIGN KEY (`CODE_SPORT`) REFERENCES `pratique_sport` (`CODE_SPORT`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

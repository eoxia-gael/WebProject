-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 30 Mai 2016 à 16:10
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `projet_web`
--

-- --------------------------------------------------------

--
-- Structure de la table `incidents`
--

CREATE TABLE IF NOT EXISTS `incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `custom_incident` varchar(50) DEFAULT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `type_incident_id` int(11) DEFAULT NULL,
  `commentaire` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type_incident_id` (`type_incident_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `incidents`
--

INSERT INTO `incidents` (`id`, `custom_incident`, `latitude`, `longitude`, `type_incident_id`, `commentaire`) VALUES
(1, 'Statue de la liberté', 40.6892, -74.0444, NULL, 'Statue de la liberté');

-- --------------------------------------------------------

--
-- Structure de la table `type_incidents`
--

CREATE TABLE IF NOT EXISTS `type_incidents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mot_de_passe` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `parametres_utilisateur_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parametres_utilisateur_id` (`parametres_utilisateur_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `mot_de_passe`, `email`, `parametres_utilisateur_id`) VALUES
(18, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(19, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(20, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(21, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(22, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(23, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(24, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(25, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(26, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(27, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(28, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(29, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(30, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(31, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(32, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(33, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(34, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(35, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(36, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(37, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(38, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(39, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(40, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(41, 'b3d8318435d8243ecc1976977cdc9de157200430', 'zizi@pipi.fr', 1),
(42, '683c64d3d749b656a64ebd92d1a2a14de7bd674f', 'prout@chevre.bite', 1),
(43, '683c64d3d749b656a64ebd92d1a2a14de7bd674f', 'prout@chevre.bite', 1),
(44, '683c64d3d749b656a64ebd92d1a2a14de7bd674f', 'prout@chevre.bite', 1),
(45, '683c64d3d749b656a64ebd92d1a2a14de7bd674f', 'prout@chevre.bite', 1),
(46, '683c64d3d749b656a64ebd92d1a2a14de7bd674f', 'prout@chevre.bitefd', 1),
(47, '683c64d3d749b656a64ebd92d1a2a14de7bd674f', 'prout@chevre.bit', 2),
(48, '683c64d3d749b656a64ebd92d1a2a14de7bd674f', 'prout@chevre.fr', 4),
(49, 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 'test@test.com', 5);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs_incidents`
--

CREATE TABLE IF NOT EXISTS `utilisateurs_incidents` (
  `utilisateur_id` int(11) NOT NULL,
  `incident_id` int(11) NOT NULL,
  `commentaire` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`utilisateur_id`,`incident_id`),
  KEY `incident_id` (`incident_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur_settings`
--

CREATE TABLE IF NOT EXISTS `utilisateur_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `credibilite_min` int(11) NOT NULL,
  `distance_incident` float NOT NULL,
  `notifications_actives` tinyint(1) NOT NULL,
  `avatar` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `utilisateur_settings`
--

INSERT INTO `utilisateur_settings` (`id`, `credibilite_min`, `distance_incident`, `notifications_actives`, `avatar`) VALUES
(1, 3, 125, 0, 'fezfez.png'),
(2, 3, 15, 1, 'sadza.png'),
(3, 3, 15, 1, 'noPicture.png'),
(4, 3, 15, 1, 'noPicture.png'),
(5, 1, 3, 0, 'bonjour.jpg');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `incidents`
--
ALTER TABLE `incidents`
  ADD CONSTRAINT `incidents_ibfk_1` FOREIGN KEY (`type_incident_id`) REFERENCES `type_incidents` (`id`);

--
-- Contraintes pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD CONSTRAINT `utilisateurs_ibfk_1` FOREIGN KEY (`parametres_utilisateur_id`) REFERENCES `utilisateur_settings` (`id`);

--
-- Contraintes pour la table `utilisateurs_incidents`
--
ALTER TABLE `utilisateurs_incidents`
  ADD CONSTRAINT `utilisateurs_incidents_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `utilisateurs_incidents_ibfk_2` FOREIGN KEY (`incident_id`) REFERENCES `incidents` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

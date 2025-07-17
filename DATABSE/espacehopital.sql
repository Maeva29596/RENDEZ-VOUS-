-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 16 Juillet 2025 à 20:48
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `espacehopital`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateurs`
--

CREATE TABLE IF NOT EXISTS `administrateurs` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `administrateurs`
--

INSERT INTO `administrateurs` (`admin_id`, `nom`, `prenom`, `email`, `mot_de_passe`, `date_creation`) VALUES
(1, 'ANDELA', 'MAEVA', 'maevaandela@gmail.com', '$2y$10$SAW2qBmVsPXmRParlENrDe/RWgbnqK2TEfrGAqOPMgKFIvykS626C', '2025-07-07 15:36:25');

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE IF NOT EXISTS `avis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `hopital_id` int(11) NOT NULL,
  `commentaire` text NOT NULL,
  `note` tinyint(4) DEFAULT NULL,
  `date_avis` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `hopital_id` (`hopital_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `hopitaux`
--

CREATE TABLE IF NOT EXISTS `hopitaux` (
  `hospital_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `statut` enum('en_attente','valide','rejete') DEFAULT 'en_attente',
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `derniere_connexion` timestamp NOT NULL,
  `la_description` text NOT NULL,
  `nom_du_chef_d_hopital` varchar(100) NOT NULL,
  `google_maps_link` varchar(255) DEFAULT NULL,
  `google_maps` text,
  PRIMARY KEY (`hospital_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Contenu de la table `hopitaux`
--

INSERT INTO `hopitaux` (`hospital_id`, `nom`, `adresse`, `ville`, `telephone`, `email`, `mot_de_passe`, `statut`, `date_inscription`, `derniere_connexion`, `la_description`, `nom_du_chef_d_hopital`, `google_maps_link`, `google_maps`) VALUES
(13, 'hopital de district de deido', 'deido', 'Douala', '+237691898168', 'andela@gmail.com', '$2y$10$uwc6dVar1yBzSQ0FVrusteNKrl0m1cxvssUVCewmhp0SX6f.ScFsS', 'valide', '2025-06-20 11:22:06', '2025-07-14 13:02:16', 'SALUT', 'BIDJECK', NULL, NULL),
(16, 'saker', 'ndokotti saker', 'Douala', '2829845892', 'saker@gmail.cpm', '$2y$10$gQ1TVL50gIkBC7llfODjN.Ra9DwgvQ8dGrvzVuEkSR0uk6CsWvVue', 'valide', '2025-06-24 11:36:52', '2025-07-14 13:02:50', 'LOL', 'hugo', NULL, NULL),
(18, 'hopital de bangue', 'bangue', 'Douala', '693689267', 'bangue@gmail.com', '$2y$10$0jrFOmMBR2.LHeagIM09iuGUTnA5tfczGhnqPkDnkVPqIgcIPcXLK', 'valide', '2025-07-14 13:08:42', '0000-00-00 00:00:00', 'meilleur', 'DR manga', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `patient_id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ville` varchar(255) NOT NULL,
  `derniere_connexion` timestamp NOT NULL,
  `sexe` varchar(10) NOT NULL,
  PRIMARY KEY (`patient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Contenu de la table `patients`
--

INSERT INTO `patients` (`patient_id`, `prenom`, `nom`, `email`, `mot_de_passe`, `telephone`, `adresse`, `date_inscription`, `ville`, `derniere_connexion`, `sexe`) VALUES
(20, 'maeva', 'andela', 'maevaandela@gmail.com', '$2y$10$RFOWgYz0YV50l0fsHAUzkOO3J.Po2mikXiJvS1fen9XyRaqeLHTcK', '237691898168', 'bangue', '2025-06-20 15:13:47', 'Douala', '0000-00-00 00:00:00', 'Femme'),
(24, 'cassandra', 'maniaga', 'maniaga@gmail.com', '$2y$10$tTv6GoLlO/pvkFPDOqVnK.nK/EtR3xOIuPwvr68YkbgVgVd4Hyryu', '99999', 'PK15', '2025-07-11 18:31:38', 'Douala', '0000-00-00 00:00:00', 'Femme');

-- --------------------------------------------------------

--
-- Structure de la table `rendez-vous`
--

CREATE TABLE IF NOT EXISTS `rendez-vous` (
  `rendez_vous_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `motif` text NOT NULL,
  `date_rendez_vous` date NOT NULL,
  `heure_rendez_vous` time NOT NULL,
  `statut` enum('en_attente','confirmé','rejeté') NOT NULL DEFAULT 'en_attente',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_mise_a_jour` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`rendez_vous_id`),
  KEY `fk_patient` (`patient_id`),
  KEY `fk_hopital` (`hospital_id`),
  KEY `fk_service` (`service_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `rendez-vous`
--

INSERT INTO `rendez-vous` (`rendez_vous_id`, `patient_id`, `hospital_id`, `service_id`, `motif`, `date_rendez_vous`, `heure_rendez_vous`, `statut`, `date_creation`, `date_mise_a_jour`) VALUES
(9, 24, 16, 11, 'MAUX DE COEUR', '2025-07-18', '23:30:00', 'confirmé', '2025-07-11 18:50:20', '2025-07-11 18:51:34');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `service_id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `hospital_id` int(11) NOT NULL,
  PRIMARY KEY (`service_id`),
  KEY `fk_services_hopitaux` (`hospital_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Contenu de la table `services`
--

INSERT INTO `services` (`service_id`, `nom`, `description`, `hospital_id`) VALUES
(11, 'cardiologie', 'traitement des maladies cardiaques', 16),
(12, 'neurologie', 'traitement des maladies liés aux sytèmes nerveux', 16),
(20, 'laboratoire', 'examens à faire', 16),
(21, 'dermatologie', 'soins de la peau', 16),
(22, 'Ophtamologie', 'traitements des maladies des yeux', 16);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `avis_ibfk_2` FOREIGN KEY (`hopital_id`) REFERENCES `hopitaux` (`hospital_id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `rendez-vous`
--
ALTER TABLE `rendez-vous`
  ADD CONSTRAINT `fk_hopital` FOREIGN KEY (`hospital_id`) REFERENCES `hopitaux` (`hospital_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_patient` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `fk_services_hopitaux` FOREIGN KEY (`hospital_id`) REFERENCES `hopitaux` (`hospital_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 20 Mai 2014 à 21:16
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Structure de la table `image`
--

CREATE TABLE IF NOT EXISTS `image` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `titre` varchar(64) NOT NULL,
  `date_ajout` datetime NOT NULL,
  `auteur` varchar(64) NOT NULL,
  `description` varchar(256) NOT NULL,
  `nom_fichier` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;


--
-- Structure de la table `infos_globales`
--

CREATE TABLE IF NOT EXISTS `infos_globales` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `meta` varchar(64) NOT NULL,
  `content` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `infos_globales`
--

INSERT INTO `infos_globales` (`id`, `meta`, `content`) VALUES
(1, 'galerie_title', 'Ma belle Galerie'),
(2, 'img_directory', 'images');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 27 Mai 2014 à 16:02
-- Version du serveur: 5.5.35-0ubuntu0.13.10.2
-- Version de PHP: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `galerie`
--

-- --------------------------------------------------------

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `image`
--

INSERT INTO `image` (`id`, `titre`, `date_ajout`, `auteur`, `description`, `nom_fichier`) VALUES
(11, 'Tortue', '2014-05-26 16:07:39', 'Les Pingouins', 'Une chouette tortue de dingue', '20140526160739.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `infos_globales`
--

CREATE TABLE IF NOT EXISTS `infos_globales` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `meta` varchar(64) NOT NULL,
  `content` varchar(128) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `infos_globales`
--

INSERT INTO `infos_globales` (`id`, `meta`, `content`) VALUES
(1, 'galerie_title', 'Ma belle galerie'),
(2, 'img_directory', 'images'),
(3, 'nb_img_par_page', '5');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) NOT NULL,
  `psswd` varchar(128) NOT NULL,
  `prenom` varchar(128) NOT NULL,
  `status` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `email`, `psswd`, `prenom`, `status`) VALUES
(8, 'contact@lespingouins.com', '$2y$10$0mEOHfPrCyTHpAsp3/wIZO43voi0o/cAP9DqbX38JQr5Q.qw1ErVa', 'Les Pingouins', 'admin'),
(9, 'matthieurebillard@yahoo.fr', '$2y$10$src43hNoTBQPkjvvcie3fOG3CDJxWEPEv/F2jUiRvZvUmPZUF89Bu', 'Matthieu', 'admin'),
(10, 'louloute@georgette.fr', '$2y$10$RC6IUEu7Ortg160kz.jySeHCj/qilA8SOOKIXS8tyiuD7b0TDaeu6', 'Georgette', 'membre');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

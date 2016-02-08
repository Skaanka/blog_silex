-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 04 Février 2016 à 01:41
-- Version du serveur :  10.0.17-MariaDB
-- Version de PHP :  5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `silex_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `billets`
--

CREATE TABLE `billets` (
  `id` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `billets`
--

INSERT INTO `billets` (`id`, `id_membre`, `titre`, `contenu`, `date_creation`) VALUES
(1, 1, 'Lorem ipsum dolor sit amet 1.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt porttitor dolor, nec porttitor leo elementum vel. Integer id lacus volutpat, rhoncus diam sit amet, commodo massa. Nulla luctus aliquet sollicitudin. Proin nec purus ut ligula pulvinar aliquet vitae ac tortor. Vivamus erat felis, pretium vel euismod non, imperdiet vel nisl. Vestibulum vestibulum, sapien eget iaculis mollis, ex purus porta nisl, sit amet vestibulum purus lacus vitae diam. Vestibulum a quam eu mauris ornare aliquet vehicula at nibh. Nullam sapien mauris, aliquet quis turpis ac, faucibus ornare turpis. Nunc commodo nec mauris euismod consectetur.\r\n\r\nQuisque ac purus et erat dapibus elementum. Sed ullamcorper finibus mattis. Nullam eros nunc, porta ut condimentum viverra, interdum at libero. Vivamus nec risus sit amet ipsum condimentum dictum vestibulum quis est. Duis et blandit ex. Nam sagittis, turpis vitae cursus pharetra, lacus diam vestibulum ipsum, ac facilisis ipsum elit sed risus. Nam dignissim eget sem at semper. Donec consequat felis dolor, vitae maximus lorem suscipit eu. Cras sed pellentesque enim. Maecenas lorem lacus, hendrerit vitae pharetra id, consectetur non turpis. Duis a ligula eu arcu fermentum mollis. Donec accumsan quis justo mollis mattis. Nam cursus arcu leo, a rhoncus nisi hendrerit quis. Sed sit amet efficitur ligula. Quisque tincidunt nunc ut massa laoreet, a vestibulum arcu suscipit. Nam fermentum nibh felis, efficitur suscipit lacus volutpat sit amet.\r\n\r\nPraesent non tellus ornare felis euismod accumsan at vitae neque. Phasellus interdum elementum varius. Aliquam rhoncus est ac mollis faucibus. Etiam risus quam, commodo laoreet elit iaculis, vestibulum cursus neque. Nullam arcu odio, gravida vitae odio ut, venenatis accumsan nisi. Mauris imperdiet nunc eu luctus molestie. Vivamus maximus diam non tellus dignissim vestibulum. Vestibulum pulvinar purus eget urna volutpat hendrerit. In ac scelerisque diam, vel placerat leo. Duis at lorem nec nibh scelerisque pulvinar.', '2016-02-03 20:00:00'),
(2, 1, 'Lorem ipsum dolor sit amet 2.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum tincidunt porttitor dolor, nec porttitor leo elementum vel. Integer id lacus volutpat, rhoncus diam sit amet, commodo massa. Nulla luctus aliquet sollicitudin. Proin nec purus ut ligula pulvinar aliquet vitae ac tortor. Vivamus erat felis, pretium vel euismod non, imperdiet vel nisl. Vestibulum vestibulum, sapien eget iaculis mollis, ex purus porta nisl, sit amet vestibulum purus lacus vitae diam. Vestibulum a quam eu mauris ornare aliquet vehicula at nibh. Nullam sapien mauris, aliquet quis turpis ac, faucibus ornare turpis. Nunc commodo nec mauris euismod consectetur.\r\n\r\nQuisque ac purus et erat dapibus elementum. Sed ullamcorper finibus mattis. Nullam eros nunc, porta ut condimentum viverra, interdum at libero. Vivamus nec risus sit amet ipsum condimentum dictum vestibulum quis est. Duis et blandit ex. Nam sagittis, turpis vitae cursus pharetra, lacus diam vestibulum ipsum, ac facilisis ipsum elit sed risus. Nam dignissim eget sem at semper. Donec consequat felis dolor, vitae maximus lorem suscipit eu. Cras sed pellentesque enim. Maecenas lorem lacus, hendrerit vitae pharetra id, consectetur non turpis. Duis a ligula eu arcu fermentum mollis. Donec accumsan quis justo mollis mattis. Nam cursus arcu leo, a rhoncus nisi hendrerit quis. Sed sit amet efficitur ligula. Quisque tincidunt nunc ut massa laoreet, a vestibulum arcu suscipit. Nam fermentum nibh felis, efficitur suscipit lacus volutpat sit amet.\r\n\r\nPraesent non tellus ornare felis euismod accumsan at vitae neque. Phasellus interdum elementum varius. Aliquam rhoncus est ac mollis faucibus. Etiam risus quam, commodo laoreet elit iaculis, vestibulum cursus neque. Nullam arcu odio, gravida vitae odio ut, venenatis accumsan nisi. Mauris imperdiet nunc eu luctus molestie. Vivamus maximus diam non tellus dignissim vestibulum. Vestibulum pulvinar purus eget urna volutpat hendrerit. In ac scelerisque diam, vel placerat leo. Duis at lorem nec nibh scelerisque pulvinar.', '2016-02-03 21:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `id_billet` int(11) NOT NULL,
  `id_membre` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `id_billet`, `id_membre`, `contenu`, `date_creation`) VALUES
(1, 1, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque auctor enim non tempor ullamcorper. Mauris lacinia sodales orci sed mattis. Morbi id.', '2016-02-03 23:00:00'),
(2, 1, 1, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque auctor enim non tempor ullamcorper. Mauris lacinia sodales orci sed mattis. Morbi id.', '2016-02-04 00:00:00');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `billets`
--
ALTER TABLE `billets`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `billets`
--
ALTER TABLE `billets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

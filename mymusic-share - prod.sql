-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 06 avr. 2021 à 04:29
-- Version du serveur :  10.4.17-MariaDB
-- Version de PHP : 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mymusic-share`
--

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `getTopDownloaded` (IN `numberOfMusic` INT)  READS SQL DATA
SELECT
    `myokndefht_tracks`.`id`,
    `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `ispending`, `listenCount`, `id_musicKey`,
    GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR ', ') AS `artistsName`,
    COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
    `myokndefht_musickey`.`musicKey`
FROM
    `myokndefht_tracks`
    INNER JOIN `myokndefht_artiststracks`
               ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
    INNER JOIN `myokndefht_artists`
               ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
    INNER
        JOIN `myokndefht_usersdownloadedtracks`
             ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
    INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
GROUP BY
    `myokndefht_usersdownloadedtracks`.`id_tracks`
ORDER BY
    `downloadCount` DESC
LIMIT numberOfMusic$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getTopListened` (IN `numberOfMusics` INT)  READS SQL DATA
SELECT
    `myokndefht_tracks`.`id`,
    `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `ispending`, `listenCount`, `id_musicKey`,
    GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR ', ') AS `artistsName`,
    COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
    `myokndefht_musickey`.`musicKey`
FROM
    `myokndefht_tracks`
    INNER JOIN `myokndefht_artiststracks`
               ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
    INNER JOIN `myokndefht_artists`
               ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
    LEFT JOIN `myokndefht_usersdownloadedtracks`
              ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
    INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
GROUP BY
    `myokndefht_tracks`.`id`
ORDER BY
    `listenCount` DESC
LIMIT numberOfMusics$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_artists`
--

CREATE TABLE `myokndefht_artists` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_artists`
--

INSERT INTO `myokndefht_artists` (`id`, `name`) VALUES
(1, 'Dimitri Vegas'),
(2, 'Moguai'),
(3, 'Like Mike'),
(4, 'DJ Snake'),
(5, 'Lil Jon'),
(6, 'Martin Garrix'),
(7, 'MOTi'),
(8, 'The Chainsmokers'),
(9, 'Instagram: @mulaoficial'),
(10, 'J Balvin'),
(11, 'Tyga'),
(12, 'David Guetta'),
(13, 'Kid Cudi'),
(14, 'Estelle'),
(15, 'Kanye West'),
(16, 'Jurri'),
(17, 'Tony'),
(18, 'Mattia'),
(19, 'Taio Cruz'),
(20, 'Sean Paul'),
(21, '6ix9ine'),
(22, 'Drake'),
(23, 'Future'),
(24, 'YG'),
(25, 'Jon Z'),
(26, 'Angèle'),
(27, 'Trois Cafés Gourmands'),
(28, 'Cris Cab'),
(29, 'Tefa'),
(30, 'Moox'),
(31, 'Macklemore'),
(32, 'Ryan Lewis'),
(33, 'Wanz'),
(34, 'Tragédie'),
(35, 'Lil Nas'),
(36, 'Pitbull'),
(37, 'Ke$ha');

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_artiststracks`
--

CREATE TABLE `myokndefht_artiststracks` (
  `id` int(11) NOT NULL,
  `id_artists` int(11) NOT NULL,
  `id_tracks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_artiststracks`
--

INSERT INTO `myokndefht_artiststracks` (`id`, `id_artists`, `id_tracks`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(4, 4, 2),
(5, 5, 2),
(6, 6, 3),
(7, 7, 3),
(8, 8, 4),
(10, 4, 6),
(11, 10, 6),
(12, 11, 6),
(13, 12, 7),
(14, 13, 7),
(15, 14, 8),
(16, 15, 8),
(17, 10, 9),
(18, 15, 10),
(19, 16, 10),
(20, 17, 10),
(21, 18, 10),
(22, 19, 11),
(23, 20, 12),
(24, 10, 12),
(25, 21, 5),
(26, 22, 13),
(27, 23, 14),
(28, 24, 15),
(29, 11, 15),
(30, 25, 15),
(31, 26, 16),
(32, 27, 17),
(33, 28, 18),
(34, 29, 18),
(35, 30, 18),
(36, 31, 19),
(37, 32, 19),
(38, 33, 19),
(39, 34, 20),
(40, 35, 21),
(41, 36, 21),
(42, 37, 21);

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_categories`
--

CREATE TABLE `myokndefht_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(70) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `imgPath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_categories`
--

INSERT INTO `myokndefht_categories` (`id`, `name`, `slug`, `imgPath`) VALUES
(2, 'Pop', 'pop', '/assets/img/categories/pop.jpg'),
(3, 'Urban', 'urban', '/assets/img/categories/urban.jpg'),
(4, 'Moombahton', 'moombahton', '/assets/img/categories/moombahton.jpg'),
(5, 'Electro', 'electro', '/assets/img/categories/electro.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_downloadliststracks`
--

CREATE TABLE `myokndefht_downloadliststracks` (
  `id` int(11) NOT NULL,
  `id_tracks` int(11) NOT NULL,
  `id_usersDownloadLists` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_musickey`
--

CREATE TABLE `myokndefht_musickey` (
  `id` int(11) NOT NULL,
  `musicKey` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_musickey`
--

INSERT INTO `myokndefht_musickey` (`id`, `musicKey`) VALUES
(1, '1A'),
(2, '1B'),
(3, '2A'),
(4, '2B'),
(5, '3A'),
(6, '3B'),
(7, '4A'),
(8, '4B'),
(9, '5A'),
(10, '5B'),
(11, '6A'),
(12, '6B'),
(13, '7A'),
(14, '7B'),
(15, '8A'),
(16, '8B'),
(17, '9A'),
(18, '9B'),
(19, '10'),
(20, '10'),
(21, '11'),
(22, '11'),
(23, '12'),
(24, '12');

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_orders`
--

CREATE TABLE `myokndefht_orders` (
  `id` int(11) NOT NULL,
  `number` varchar(12) NOT NULL,
  `orderDate` datetime NOT NULL,
  `deliveryDate` datetime NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `id_subscriptions` int(11) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_subscriptions`
--

CREATE TABLE `myokndefht_subscriptions` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `description` text NOT NULL,
  `duration` int(11) NOT NULL,
  `numberOfDownload` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_subscriptions`
--

INSERT INTO `myokndefht_subscriptions` (`id`, `name`, `price`, `description`, `duration`, `numberOfDownload`) VALUES
(1, 'Premium', 15.99, 'azdokzjpaodhpazdazhjp', 1, 2000);

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_tracks`
--

CREATE TABLE `myokndefht_tracks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `bpm` int(11) NOT NULL,
  `bitrate` int(11) NOT NULL,
  `releaseDate` datetime NOT NULL DEFAULT current_timestamp(),
  `path` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `isPending` tinyint(1) NOT NULL,
  `listenCount` int(11) NOT NULL,
  `downloadCount` int(11) NOT NULL,
  `id_categories` int(11) NOT NULL,
  `id_musicKey` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_tracks`
--

INSERT INTO `myokndefht_tracks` (`id`, `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `downloadCount`, `id_categories`, `id_musicKey`) VALUES
(1, 'Mammoth', 128, 320, '2021-04-06 04:02:29', '/assets/musics/categories/37a2acb267565316176745499c3f6da860f400.mp3', '37a2acb267565316176745499c3f6da860f400', 0, 0, 0, 5, 11),
(2, 'I Love Turn Down For What (NJ -  Mashup)', 100, 320, '2021-04-06 04:02:59', '/assets/musics/categories/65c531ec752c6916176745798371ccc9d0fd3a.mp3', '65c531ec752c6916176745798371ccc9d0fd3a', 0, 1, 0, 5, 24),
(3, 'Virus (How About Now)', 128, 320, '2021-04-06 04:03:21', '/assets/musics/categories/fdb04f2cbe120b1617674601ac605a6b83a7ca.mp3', 'fdb04f2cbe120b1617674601ac605a6b83a7ca', 0, 0, 0, 5, 7),
(4, '#SELFIE', 128, 320, '2021-04-06 04:03:52', '/assets/musics/categories/8d4c7193842ea81617674632aa5e97bfec602e.mp3', '8d4c7193842ea81617674632aa5e97bfec602e', 0, 0, 0, 5, 9),
(5, 'YAYA (Mula Deejay Rmx)', 105, 320, '2021-04-06 04:04:11', '/assets/musics/categories/fd441460abc2d81617674651149680f5e56d00.mp3', 'fd441460abc2d81617674651149680f5e56d00', 0, 2, 0, 4, 23),
(6, 'Loco Contigo (Da Phonk -  Extended Edit)', 104, 320, '2021-04-06 04:05:00', '/assets/musics/categories/818fd07173d7401617674700ee1c678eb47192.mp3', '818fd07173d7401617674700ee1c678eb47192', 0, 8, 0, 4, 21),
(7, 'Memories (Rogerson\'s -  Rewind)', 130, 320, '2021-04-06 04:05:16', '/assets/musics/categories/d5efe9224729a91617674716300e62428bcecd.mp3', 'd5efe9224729a91617674716300e62428bcecd', 0, 45, 0, 4, 3),
(8, 'American Boy (YANNIS - Remix)', 105, 320, '2021-04-06 04:05:55', '/assets/musics/categories/d2ae42a8d09179161767475502f0f7cf7d9ba9.mp3', 'd2ae42a8d09179161767475502f0f7cf7d9ba9', 0, 20, 0, 4, 24),
(9, 'Amarillo (Jusoan &amp; R4R -  Remix)', 100, 320, '2021-04-06 04:06:19', '/assets/musics/categories/696f817c6ae12b1617674779aa09ca95ed7e6a.mp3', '696f817c6ae12b1617674779aa09ca95ed7e6a', 0, 4, 0, 4, 2),
(10, 'Stronger (Jurri &amp; Tony x Mattia -  Bootleg)', 108, 320, '2021-04-06 04:06:46', '/assets/musics/categories/2f45816a568d89161767480698b223da709c35.mp3', '2f45816a568d89161767480698b223da709c35', 0, 0, 0, 4, 5),
(11, 'Dynamite (Alpha Party -  Bootleg)', 128, 320, '2021-04-06 04:06:59', '/assets/musics/categories/0842351b3594961617674819c81366ad0f6c6d.mp3', '0842351b3594961617674819c81366ad0f6c6d', 0, 0, 0, 4, 19),
(12, 'Contra La Pared (M3B8 -  Remix)', 100, 320, '2021-04-06 04:08:39', '/assets/musics/categories/50878b7bdacd991617674919bc754def0d8d7d.mp3', '50878b7bdacd991617674919bc754def0d8d7d', 0, 0, 0, 4, 20),
(13, 'God\'s Plan ', 154, 320, '2021-04-06 04:11:22', '/assets/musics/categories/0aca47879ad8a1161767508245c936e9110ac0.mp3', '0aca47879ad8a1161767508245c936e9110ac0', 0, 0, 0, 3, 5),
(14, 'Life Is Good ', 71, 320, '2021-04-06 04:11:39', '/assets/musics/categories/334a73499759cb1617675099a5fc608e187cd6.mp3', '334a73499759cb1617675099a5fc608e187cd6', 0, 0, 0, 3, 17),
(15, 'Go Loko (DJ Nasa VIP Redrum)', 105, 320, '2021-04-06 04:12:06', '/assets/musics/categories/f7426e2164e79c1617675126a38e079b8fe0e1.mp3', 'f7426e2164e79c1617675126a38e079b8fe0e1', 0, 0, 0, 3, 20),
(16, 'Tout Oublier (Charles J - Rework)', 120, 320, '2021-04-06 04:12:39', '/assets/musics/categories/c7315ea1e271731617675159c9e73f31899f11.mp3', 'c7315ea1e271731617675159c9e73f31899f11', 0, 0, 0, 2, 21),
(17, 'A Nos Souvenirs (Jeremy NS - Extended)', 133, 320, '2021-04-06 04:13:25', '/assets/musics/categories/7e2c74ebc422b6161767520547347ed671ba19.mp3', '7e2c74ebc422b6161767520547347ed671ba19', 0, 6, 0, 2, 24),
(18, 'Englishman In New York (Willy William - Club Mix)', 108, 320, '2021-04-06 04:15:01', '/assets/musics/categories/7620780359749016176753014f1353170c84f1.mp3', '7620780359749016176753014f1353170c84f1', 0, 0, 0, 2, 22),
(19, 'THRIFT SHOP (Starjack - Hype Re-Drum)', 95, 320, '2021-04-06 04:15:24', '/assets/musics/categories/3dedd34da9bb951617675324e3bafdef27579f.mp3', '3dedd34da9bb951617675324e3bafdef27579f', 0, 0, 0, 2, 9),
(20, 'Hey Oh (YANISS - Remix)', 105, 320, '2021-04-06 04:15:49', '/assets/musics/categories/a597755985cae416176753492d6d0b37a108d9.mp3', 'a597755985cae416176753492d6d0b37a108d9', 0, 4, 0, 2, 23),
(21, 'Old Town Road (Drezz \'TIMBER\' Edit)', 130, 320, '2021-04-06 04:16:34', '/assets/musics/categories/a3b90cf455eb2c16176753947d7a46287e463e.mp3', 'a3b90cf455eb2c16176753947d7a46287e463e', 0, 0, 0, 2, 2);

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_userrole`
--

CREATE TABLE `myokndefht_userrole` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_userrole`
--

INSERT INTO `myokndefht_userrole` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'User');

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_users`
--

CREATE TABLE `myokndefht_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(60) NOT NULL,
  `registerDate` datetime NOT NULL DEFAULT current_timestamp(),
  `remainingDownload` int(11) DEFAULT NULL,
  `confirmationToken` varchar(255) DEFAULT NULL,
  `confirmationTokenExpire` datetime DEFAULT NULL,
  `confirmationDate` datetime DEFAULT NULL,
  `passwordResetToken` varchar(255) DEFAULT NULL,
  `passwordResetExpire` datetime DEFAULT NULL,
  `id_userRole` int(11) NOT NULL DEFAULT 2,
  `rememberMeToken` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_users`
--

INSERT INTO `myokndefht_users` (`id`, `username`, `email`, `password`, `registerDate`, `remainingDownload`, `confirmationToken`, `confirmationTokenExpire`, `confirmationDate`, `passwordResetToken`, `passwordResetExpire`, `id_userRole`, `rememberMeToken`) VALUES
(1, 'Théo Dulieu', 'theo.d02290@gmail.com', '$2y$10$3yKhyOTPQH9KhpaTnaFYsOLDy8eN/WOyL2TuxWK95eK.3lkhDrlXW', '2021-04-06 03:56:25', 49, NULL, NULL, '2021-04-06 03:56:32', NULL, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_usersdownloadedtracks`
--

CREATE TABLE `myokndefht_usersdownloadedtracks` (
  `id` int(11) NOT NULL,
  `id_users` int(11) NOT NULL,
  `id_tracks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `myokndefht_usersdownloadedtracks`
--

INSERT INTO `myokndefht_usersdownloadedtracks` (`id`, `id_users`, `id_tracks`) VALUES
(1, 1, 1),
(2, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `myokndefht_usersdownloadlists`
--

CREATE TABLE `myokndefht_usersdownloadlists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_users` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `myokndefht_artists`
--
ALTER TABLE `myokndefht_artists`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `myokndefht_artiststracks`
--
ALTER TABLE `myokndefht_artiststracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artistsTracks_artists_FK` (`id_artists`),
  ADD KEY `artistsTracks_tracks0_FK` (`id_tracks`);

--
-- Index pour la table `myokndefht_categories`
--
ALTER TABLE `myokndefht_categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `myokndefht_downloadliststracks`
--
ALTER TABLE `myokndefht_downloadliststracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `downloadListsTracks_tracks_FK` (`id_tracks`),
  ADD KEY `downloadListsTracks_usersDownloadLists0_FK` (`id_usersDownloadLists`);

--
-- Index pour la table `myokndefht_musickey`
--
ALTER TABLE `myokndefht_musickey`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `myokndefht_orders`
--
ALTER TABLE `myokndefht_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_subscriptions_FK` (`id_subscriptions`),
  ADD KEY `orders_users0_FK` (`id_users`);

--
-- Index pour la table `myokndefht_subscriptions`
--
ALTER TABLE `myokndefht_subscriptions`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `myokndefht_tracks`
--
ALTER TABLE `myokndefht_tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tracks_categories_FK` (`id_categories`),
  ADD KEY `tracks_musicKey0_FK` (`id_musicKey`);

--
-- Index pour la table `myokndefht_userrole`
--
ALTER TABLE `myokndefht_userrole`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `myokndefht_users`
--
ALTER TABLE `myokndefht_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_userRole_FK` (`id_userRole`);

--
-- Index pour la table `myokndefht_usersdownloadedtracks`
--
ALTER TABLE `myokndefht_usersdownloadedtracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usersDownloadedTracks_users_FK` (`id_users`),
  ADD KEY `usersDownloadedTracks_tracks0_FK` (`id_tracks`);

--
-- Index pour la table `myokndefht_usersdownloadlists`
--
ALTER TABLE `myokndefht_usersdownloadlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usersDownloadLists_users_FK` (`id_users`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `myokndefht_artists`
--
ALTER TABLE `myokndefht_artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT pour la table `myokndefht_artiststracks`
--
ALTER TABLE `myokndefht_artiststracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `myokndefht_categories`
--
ALTER TABLE `myokndefht_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `myokndefht_downloadliststracks`
--
ALTER TABLE `myokndefht_downloadliststracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `myokndefht_musickey`
--
ALTER TABLE `myokndefht_musickey`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `myokndefht_orders`
--
ALTER TABLE `myokndefht_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `myokndefht_subscriptions`
--
ALTER TABLE `myokndefht_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `myokndefht_tracks`
--
ALTER TABLE `myokndefht_tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `myokndefht_userrole`
--
ALTER TABLE `myokndefht_userrole`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `myokndefht_users`
--
ALTER TABLE `myokndefht_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `myokndefht_usersdownloadedtracks`
--
ALTER TABLE `myokndefht_usersdownloadedtracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `myokndefht_usersdownloadlists`
--
ALTER TABLE `myokndefht_usersdownloadlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `myokndefht_artiststracks`
--
ALTER TABLE `myokndefht_artiststracks`
  ADD CONSTRAINT `artistsTracks_artists_FK` FOREIGN KEY (`id_artists`) REFERENCES `myokndefht_artists` (`id`),
  ADD CONSTRAINT `artistsTracks_tracks0_FK` FOREIGN KEY (`id_tracks`) REFERENCES `myokndefht_tracks` (`id`);

--
-- Contraintes pour la table `myokndefht_downloadliststracks`
--
ALTER TABLE `myokndefht_downloadliststracks`
  ADD CONSTRAINT `downloadListsTracks_tracks_FK` FOREIGN KEY (`id_tracks`) REFERENCES `myokndefht_tracks` (`id`),
  ADD CONSTRAINT `downloadListsTracks_usersDownloadLists0_FK` FOREIGN KEY (`id_usersDownloadLists`) REFERENCES `myokndefht_usersdownloadlists` (`id`);

--
-- Contraintes pour la table `myokndefht_orders`
--
ALTER TABLE `myokndefht_orders`
  ADD CONSTRAINT `orders_subscriptions_FK` FOREIGN KEY (`id_subscriptions`) REFERENCES `myokndefht_subscriptions` (`id`),
  ADD CONSTRAINT `orders_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `myokndefht_users` (`id`);

--
-- Contraintes pour la table `myokndefht_tracks`
--
ALTER TABLE `myokndefht_tracks`
  ADD CONSTRAINT `tracks_categories_FK` FOREIGN KEY (`id_categories`) REFERENCES `myokndefht_categories` (`id`),
  ADD CONSTRAINT `tracks_musicKey0_FK` FOREIGN KEY (`id_musicKey`) REFERENCES `myokndefht_musickey` (`id`);

--
-- Contraintes pour la table `myokndefht_users`
--
ALTER TABLE `myokndefht_users`
  ADD CONSTRAINT `users_userRole_FK` FOREIGN KEY (`id_userRole`) REFERENCES `myokndefht_userrole` (`id`);

--
-- Contraintes pour la table `myokndefht_usersdownloadedtracks`
--
ALTER TABLE `myokndefht_usersdownloadedtracks`
  ADD CONSTRAINT `usersDownloadedTracks_tracks0_FK` FOREIGN KEY (`id_tracks`) REFERENCES `myokndefht_tracks` (`id`),
  ADD CONSTRAINT `usersDownloadedTracks_users_FK` FOREIGN KEY (`id_users`) REFERENCES `myokndefht_users` (`id`);

--
-- Contraintes pour la table `myokndefht_usersdownloadlists`
--
ALTER TABLE `myokndefht_usersdownloadlists`
  ADD CONSTRAINT `usersDownloadLists_users_FK` FOREIGN KEY (`id_users`) REFERENCES `myokndefht_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------


#------------------------------------------------------------
# Table: subscriptions
#------------------------------------------------------------

CREATE TABLE `subscriptions`
(
    `id`               INT AUTO_INCREMENT NOT NULL,
    `name`             VARCHAR(50)        NOT NULL,
    `price`            FLOAT              NOT NULL,
    `description`      TEXT               NOT NULL,
    `duration`         INT                NOT NULL,
    `numberOfDownload` INT                NOT NULL,
    CONSTRAINT `subscriptions_PK` PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Déchargement des données de la table `userRole`
--
INSERT INTO
    `subscriptions` (`id`, `name`, `price`, `description`, `duration`, `numberOfDownload`)
VALUES
    (1, 'Premium', 15.99, 'azdokzjpaodhpazdazhjp', 1, 2000);


#------------------------------------------------------------
# Table: categories
#------------------------------------------------------------

CREATE TABLE `categories`
(
    `id`      INT AUTO_INCREMENT NOT NULL,
    `name`    VARCHAR(70)        NOT NULL,
    `slug`    VARCHAR(50)        NOT NULL,
    `imgPath` VARCHAR(255)       NOT NULL,
    CONSTRAINT `categories_PK` PRIMARY KEY (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: userRole
#------------------------------------------------------------

CREATE TABLE `userRole`
(
    `id`   INT AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(50)        NOT NULL,
    CONSTRAINT `userRole_PK` PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Déchargement des données de la table `userRole`
--
INSERT INTO
    `userRole` (`id`, `name`)
VALUES
    (1, 'Admin'),
    (2, 'User');

#------------------------------------------------------------
# Table: users
#------------------------------------------------------------

CREATE TABLE `users`
(
    `id`                      INT AUTO_INCREMENT NOT NULL,
    `username`                VARCHAR(50)        NOT NULL,
    `email`                   VARCHAR(150)       NOT NULL,
    `password`                VARCHAR(60)        NOT NULL,
    `registerDate`            DATETIME           NOT NULL,
    `remainingDownload`       INT,
    `confirmationToken`       VARCHAR(255),
    `confirmationTokenExpire` DATETIME,
    `confirmationDate`        DATETIME,
    `passwordResetToken`      VARCHAR(255),
    `passwordResetExpire`     DATETIME,
    `id_userRole`             INT                NOT NULL,
    `rememberMeToken`         VARCHAR(255),
    CONSTRAINT `users_PK` PRIMARY KEY (`id`),
    CONSTRAINT `users_userRole_FK` FOREIGN KEY (`id_userRole`) REFERENCES `userRole` (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: orders
#------------------------------------------------------------

CREATE TABLE `orders`
(
    `id`               INT AUTO_INCREMENT NOT NULL,
    `number`           VARCHAR(12)        NOT NULL,
    `orderDate`        DATETIME           NOT NULL,
    `deliveryDate`     DATETIME           NOT NULL,
    `isActive`         BOOL               NOT NULL,
    `id_subscriptions` INT                NOT NULL,
    `id_users`         INT                NOT NULL,
    CONSTRAINT `orders_PK` PRIMARY KEY (`id`),
    CONSTRAINT `orders_subscriptions_FK` FOREIGN KEY (`id_subscriptions`) REFERENCES `subscriptions` (`id`),
    CONSTRAINT `orders_users0_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: artists
#------------------------------------------------------------

CREATE TABLE `artists`
(
    `id`   INT AUTO_INCREMENT NOT NULL,
    `name` VARCHAR(100)       NOT NULL,
    CONSTRAINT `artists_PK` PRIMARY KEY (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: usersDownloadLists
#------------------------------------------------------------

CREATE TABLE `usersDownloadLists`
(
    `id`       INT AUTO_INCREMENT NOT NULL,
    `name`     VARCHAR(255)       NOT NULL,
    `id_users` INT                NOT NULL,
    CONSTRAINT `usersDownloadLists_PK` PRIMARY KEY (`id`),
    CONSTRAINT `usersDownloadLists_users_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: musicKey
#------------------------------------------------------------

CREATE TABLE `musicKey`
(
    `id`       INT AUTO_INCREMENT NOT NULL,
    `musicKey` VARCHAR(2)         NOT NULL,
    CONSTRAINT `musicKey_PK` PRIMARY KEY (`id`)
) ENGINE = InnoDB;

--
-- Déchargement des données de la table `musicKey`
--

INSERT INTO
    `musicKey` (`id`, `musicKey`)
VALUES
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
    (19, '10A'),
    (20, '10B'),
    (21, '11A'),
    (22, '11B'),
    (23, '12A'),
    (24, '12B');

#------------------------------------------------------------
# Table: tracks
#------------------------------------------------------------

CREATE TABLE `tracks`
(
    `id`            INT AUTO_INCREMENT NOT NULL,
    `title`         VARCHAR(255)       NOT NULL,
    `bpm`           INT                NOT NULL,
    `bitrate`       INT                NOT NULL,
    `releaseDate`   DATETIME           NOT NULL,
    `path`          VARCHAR(255)       NOT NULL,
    `hash`          VARCHAR(255)       NOT NULL,
    `isPending`     BOOL               NOT NULL,
    `listenCount`   INT                NOT NULL,
    `downloadCount` INT                NOT NULL,
    `id_categories` INT                NOT NULL,
    `id_musicKey`   INT                NOT NULL,
    CONSTRAINT `tracks_PK` PRIMARY KEY (`id`),
    CONSTRAINT `tracks_categories_FK` FOREIGN KEY (`id_categories`) REFERENCES `categories` (`id`),
    CONSTRAINT `tracks_musicKey0_FK` FOREIGN KEY (`id_musicKey`) REFERENCES `musicKey` (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: artistsTracks
#------------------------------------------------------------

CREATE TABLE `artistsTracks`
(
    `id`         INT AUTO_INCREMENT NOT NULL,
    `id_artists` INT                NOT NULL,
    `id_tracks`  INT                NOT NULL,
    CONSTRAINT `artistsTracks_PK` PRIMARY KEY (`id`),
    CONSTRAINT `artistsTracks_artists_FK` FOREIGN KEY (`id_artists`) REFERENCES `artists` (`id`),
    CONSTRAINT `artistsTracks_tracks0_FK` FOREIGN KEY (`id_tracks`) REFERENCES `tracks` (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: downloadListsTracks
#------------------------------------------------------------

CREATE TABLE `downloadListsTracks`
(
    `id`                    INT AUTO_INCREMENT NOT NULL,
    `id_tracks`             INT                NOT NULL,
    `id_usersDownloadLists` INT                NOT NULL,
    CONSTRAINT `downloadListsTracks_PK` PRIMARY KEY (`id`),
    CONSTRAINT `downloadListsTracks_tracks_FK` FOREIGN KEY (`id_tracks`) REFERENCES `tracks` (`id`),
    CONSTRAINT `downloadListsTracks_usersDownloadLists0_FK` FOREIGN KEY (`id_usersDownloadLists`) REFERENCES `usersDownloadLists` (`id`)
) ENGINE = InnoDB;


#------------------------------------------------------------
# Table: usersDownloadedTracks
#------------------------------------------------------------

CREATE TABLE `usersDownloadedTracks`
(
    `id`        INT AUTO_INCREMENT NOT NULL,
    `id_users`  INT                NOT NULL,
    `id_tracks` INT                NOT NULL,
    CONSTRAINT `usersDownloadedTracks_PK` PRIMARY KEY (`id`),
    CONSTRAINT `usersDownloadedTracks_users_FK` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`),
    CONSTRAINT `usersDownloadedTracks_tracks0_FK` FOREIGN KEY (`id_tracks`) REFERENCES `tracks` (`id`)
) ENGINE = InnoDB;

ALTER TABLE `users`
    CHANGE `id_userRole` `id_userRole` INT(11) NOT NULL DEFAULT '2';
ALTER TABLE `users`
    CHANGE `registerDate` `registerDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `tracks`
    CHANGE `releaseDate` `releaseDate` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;


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
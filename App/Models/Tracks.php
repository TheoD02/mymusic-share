<?php


namespace App\Models;


use App\Models\Scheme\TracksScheme;
use PDO;

class Tracks extends TracksScheme
{

    /**
     * Retourne la liste des musique
     *
     * @param bool $getPendingTracks Récupérer les musique en attente si true
     * @param int  $startOffset
     * @param int  $limit
     *
     * @return Tracks[]|false
     */
    public function getTracksList(bool $getPendingTracks = false, int $startOffset = 0, int $limit = 100): array|false
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `id_musicKey`, `id_categories`,
                                        GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName`,
                                        COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
                                        `myokndefht_musickey`.`musicKey`,
                                        `myokndefht_categories`.`id` AS `categoryId`, `myokndefht_categories`.`name` AS `categoryName`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        LEFT JOIN `myokndefht_usersdownloadedtracks`
                                                  ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
                                        INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                        INNER JOIN `myokndefht_categories` ON `myokndefht_tracks`.`id_categories` = `myokndefht_categories`.`id`
                                    ' . ($getPendingTracks ? '' : 'WHERE `isPending` = 0') . '
                                    GROUP BY
                                        `myokndefht_tracks`.`id`
                                    ORDER BY
                                        `myokndefht_tracks`.`releaseDate` DESC 
                                    LIMIT ' . $startOffset . ', ' . $limit);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retourne la liste des musique en attente de mise en ligne
     *
     * @param int $startOffset
     * @param int $limit
     *
     * @return Tracks[]|false
     */
    public function getPendingTracksList(int $startOffset = 0, int $limit = 100): array|false
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `id_musicKey`, `id_categories`,
                                        GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName`,
                                        COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
                                        `myokndefht_musickey`.`musicKey`,
                                        `myokndefht_categories`.`id` AS `categoryId`, `myokndefht_categories`.`name` AS `categoryName`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        LEFT JOIN `myokndefht_usersdownloadedtracks`
                                                  ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
                                        INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                        INNER JOIN `myokndefht_categories` ON `myokndefht_tracks`.`id_categories` = `myokndefht_categories`.`id`
                                        WHERE `isPending` = 1                                    
                                    GROUP BY
                                        `myokndefht_tracks`.`id`
                                    ORDER BY
                                        `myokndefht_tracks`.`releaseDate` DESC
                                    LIMIT ' . $startOffset . ', ' . $limit);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Renvoi une liste de musique d'une catégorie
     *
     * @param int $startOffset
     * @param int $limit
     *
     * @return Tracks[]|false
     */
    public function getTrackListByCategories(int $startOffset, int $limit): array|false
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `id_musicKey`, `id_categories`,
                                        GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName`,
                                        COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
                                        `myokndefht_musickey`.`musicKey`,
                                        `myokndefht_categories`.`id` AS `categoryId`, `myokndefht_categories`.`name` AS `categoryName`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        LEFT JOIN `myokndefht_usersdownloadedtracks`
                                                  ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
                                        INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                        INNER JOIN `myokndefht_categories` ON `myokndefht_tracks`.`id_categories` = `myokndefht_categories`.`id`
                                    WHERE `id_categories` = :idCategory AND `isPending` = 0
                                    GROUP BY
                                        `myokndefht_tracks`.`id`
                                    ORDER BY
                                        `myokndefht_tracks`.`releaseDate` DESC
                                    LIMIT ' . $startOffset . ', ' . $limit);
        $stmt->bindValue(':idCategory', $this->getIdCategories(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Ajoute une musique
     *
     * @return bool
     */
    public function addMusic(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_tracks` (`title`, `bpm`, `bitrate`, `path`, `hash`, `isPending`, `id_categories`, `id_musicKey`) 
                                                        VALUES (:title, :bpm, :bitrate, :path, :hash, :isPending, :idCategory, :musicKey) ');
        $stmt->bindValue(':title', $this->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(':bpm', $this->getBpm(), PDO::PARAM_INT);
        $stmt->bindValue(':bitrate', $this->getBitrate(), PDO::PARAM_INT);
        $stmt->bindValue(':path', $this->getPath(), PDO::PARAM_STR);
        $stmt->bindValue(':hash', $this->getHash(), PDO::PARAM_STR);
        $stmt->bindValue(':isPending', $this->isPending(), PDO::PARAM_BOOL);
        $stmt->bindValue(':idCategory', $this->getIdCategories(), PDO::PARAM_INT);
        $stmt->bindValue(':musicKey', $this->getIdMusicKey(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Supprime une musique via son ID
     *
     * @return bool
     */
    public function deleteTrackById(): bool
    {
        $stmt = $this->prepare('DELETE FROM `myokndefht_tracks` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retourne les informations d'une musique
     *
     * @return Tracks|false
     */
    public function getTrackById(): Tracks|false
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `id_musicKey`, `id_categories`,
                                        GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName`,
                                        COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
                                        `myokndefht_musickey`.`musicKey`,
                                        `myokndefht_categories`.`id` AS `categoryId`, `myokndefht_categories`.`name` AS `categoryName`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        LEFT JOIN `myokndefht_usersdownloadedtracks`
                                                  ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
                                        INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                        INNER JOIN `myokndefht_categories` ON `myokndefht_tracks`.`id_categories` = `myokndefht_categories`.`id`
                                    WHERE `myokndefht_tracks`.`id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Met à jour les informations d'une musique
     *
     * @return bool
     */
    public function updateTrackInfoById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_tracks` SET `title` = :title, `bpm` = :bpm, `bitrate` = :bitrate, `path` = :path, `id_musicKey` = :musicKey, `id_categories` = :category, `isPending` = :isPending WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':title', $this->getTitle(), PDO::PARAM_STR);
        $stmt->bindValue(':bpm', $this->getBpm(), PDO::PARAM_INT);
        $stmt->bindValue(':bitrate', $this->getBitrate(), PDO::PARAM_INT);
        $stmt->bindValue(':path', $this->getPath(), PDO::PARAM_STR);
        $stmt->bindValue(':musicKey', $this->getIdMusicKey(), PDO::PARAM_INT);
        $stmt->bindValue(':category', $this->getIdCategories(), PDO::PARAM_INT);
        $stmt->bindValue(':isPending', $this->isPending(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retourne les id's des artistes
     *
     * @return array|bool
     */
    public function getArtistsIds(): array|bool
    {
        $stmt = $this->prepare('SELECT `id_artists` FROM `myokndefht_tracks` INNER JOIN `myokndefht_artiststracks` ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks` WHERE `myokndefht_tracks`.`id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retourne les information du musique via son HASH
     *
     * @return Tracks|false
     */
    public function getMp3ByHash(): Tracks|false
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `id_musicKey`, `id_categories`,
                                        GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName`,
                                        COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
                                        `myokndefht_musickey`.`musicKey`,
                                        `myokndefht_categories`.`id` AS `categoryId`, `myokndefht_categories`.`name` AS `categoryName`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        LEFT JOIN `myokndefht_usersdownloadedtracks`
                                                  ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
                                        INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                        INNER JOIN `myokndefht_categories` ON `myokndefht_tracks`.`id_categories` = `myokndefht_categories`.`id`
                                    WHERE `myokndefht_tracks`.`hash` = :hash');
        $stmt->bindValue(':hash', $this->getHash(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Ajoute une écoute sur une musique via le hash
     *
     * @return bool
     */
    public function addListenOnTrackByHash(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_tracks` SET `listenCount` = `listenCount` + 1 WHERE `hash` = :hash');
        $stmt->bindValue(':hash', $this->getHash(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Retourne le nombre de musique
     *
     * @return int
     */
    public function getTotalNumberOfTracks(): int
    {
        $stmt = $this->query('SELECT COUNT(`id`) AS `tracksCount` FROM `myokndefht_tracks`');
        $stmt->execute();
        return $stmt->fetch()->tracksCount;
    }

    /**
     * Retourne le nombre de musique totale d'une catégorie
     *
     * @return int
     */
    public function getTotalNumberOfTracksInCategory(): int
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `tracksCount` FROM `myokndefht_tracks` WHERE `id_categories` = :idCategory');
        $stmt->bindValue(':idCategory', $this->getIdCategories(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()->tracksCount;
    }

    /**
     * Retourne le nombre de musique en attente
     *
     * @return int
     */
    public function getTotalNumberOfPendingTracks(): int
    {
        $stmt = $this->query('SELECT COUNT(`id`) AS `tracksCount` FROM `myokndefht_tracks` WHERE `isPending` = 1');
        return $stmt->fetch()->tracksCount;
    }

    /**
     * Retourne le nombre de musique
     *
     * @return int
     */
    public function getTotalNumberOfTracksByCategory(): int
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `tracksCount` FROM `myokndefht_tracks` WHERE `id_categories` = :idCategory');
        $stmt->bindValue(':idCategory', $this->getIdCategories(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()->tracksCount;
    }

    /**
     * Retourne le top des musique écouter selon la limite
     *
     * @param int $limit Nombre de musique maximum à récupérer
     *
     * @return Tracks[]|false
     */
    public function getTopListenedTracks(int $limit): array|false
    {
        $stmt = $this->prepare('CALL `getTopListened`(' . $limit . ');');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retourne le top des musique télécharger selon la limite
     *
     * @param int $limit Nombre de musique maximum à récupérer
     *
     * @return Tracks[]|false
     */
    public function getTopDownloadedTracks(int $limit): array|false
    {
        $stmt = $this->prepare('CALL `getTopDownloaded`(' . $limit . ');');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retourne un object avec l'id de la musique via le Hash
     *
     * @return Tracks|false
     */
    public function getTrackIdByHash(): Tracks|false
    {
        $stmt = $this->prepare('SELECT `id` FROM `myokndefht_tracks` WHERE `hash` = :hash');
        $stmt->bindValue(':hash', $this->getHash(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Recherche des termes dans une musique (titre, artiste)
     *
     * @param string $terms
     *
     * @return Tracks[]|false
     */
    public function searchTrackByTerms(string $terms): array|false
    {
        $query = 'SELECT 
                                        `myokndefht_tracks`.`id`,
                                        `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `id_musicKey`, `id_categories`,
                                        GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName`,
                                        COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
                                        `myokndefht_musickey`.`musicKey`,
                                        `myokndefht_categories`.`id` AS `categoryId`, `myokndefht_categories`.`name` AS `categoryName`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        LEFT JOIN `myokndefht_usersdownloadedtracks`
                                                  ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
                                        INNER JOIN `myokndefht_musickey` ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                        INNER JOIN `myokndefht_categories` ON `myokndefht_tracks`.`id_categories` = `myokndefht_categories`.`id` WHERE ';

        $bindedValue        = [];
        $termCount          = 0;
        $arrayOfSearchTerms = explode(' ', $terms);
        foreach ($arrayOfSearchTerms as $term)
        {
            if ($termCount !== 0)
            {
                $query .= ' OR ';
            }
            $query                               .= ' `myokndefht_tracks`.`title`' . ' LIKE :term' . $termCount . ' OR `myokndefht_artists`.`name` LIKE :term' . $termCount;
            $bindedValue[':term' . $termCount++] = '%' . $term . '%';
        }
        $query .= ' GROUP BY `myokndefht_artiststracks`.`id_tracks`';
        $stmt  = $this->prepare($query);
        foreach ($bindedValue as $bindName => $bindValue)
        {
            $stmt->bindValue($bindName, $bindValue, PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}
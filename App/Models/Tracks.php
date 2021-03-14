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
     *
     * @return Tracks[]|false
     */
    public function getTracksList(bool $getPendingTracks = false): array|false
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`,
                                        `bpm`,
                                        `bitrate`,
                                        `releaseDate`,
                                        `path`,
                                        `hash`,
                                        `isPending`,
                                        GROUP_CONCAT(\'<a href="\', `myokndefht_artists`.`id`, \'">\', `name`, \'</a>\' SEPARATOR \',  \') AS \'artistsName\',
                                        `myokndefht_musickey`.`musicKey`,
                                        `id_categories`,
                                        `id_musicKey`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artists`.`id` = `myokndefht_artiststracks`.`id_artists`
                                        INNER JOIN `myokndefht_musickey`
                                                   ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                    ' . ($getPendingTracks ? '' : 'WHERE `isPending` = 0') . '
                                    GROUP BY
                                        `myokndefht_tracks`.`id`
                                    ORDER BY
                                        `myokndefht_tracks`.`releaseDate` DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retourne la liste des musique en attente de mise en ligne
     *
     * @return Tracks[]|false
     */
    public function getPendingTracksList(): array|false
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`,
                                        `bpm`,
                                        `bitrate`,
                                        `releaseDate`,
                                        `path`,
                                        `hash`,
                                        `isPending`,
                                        GROUP_CONCAT(\'<a href="\', `myokndefht_artists`.`id`, \'">\', `name`, \'</a>\' SEPARATOR \',  \') AS \'artistsName\',
                                        `id_categories`,
                                        `id_musicKey`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artists`.`id` = `myokndefht_artiststracks`.`id_artists`
                                        INNER JOIN `myokndefht_musickey`
                                                   ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                     WHERE `isPending` = 1
                                    GROUP BY
                                        `myokndefht_tracks`.`id`
                                    ORDER BY
                                        `myokndefht_tracks`.`releaseDate` DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     *
     */
    public function getTrackListByCategories(int $startOffset, int $limit)
    {
        $stmt = $this->prepare('SELECT
                                        `myokndefht_tracks`.`id`,
                                        `title`,
                                        `bpm`,
                                        `bitrate`,
                                        `releaseDate`,
                                        `path`,
                                        `hash`,
                                        `isPending`,
                                        GROUP_CONCAT(\'<a href="\', `myokndefht_artists`.`id`, \'">\', `name`, \'</a>\' SEPARATOR \',  \') AS \'artistsName\',
                                        `myokndefht_musickey`.`musicKey`,
                                        `id_categories`,
                                        `id_musicKey`
                                    FROM
                                        `myokndefht_tracks`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artists`.`id` = `myokndefht_artiststracks`.`id_artists`
                                        INNER JOIN `myokndefht_musickey`
                                                   ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
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
        $stmt = $this->prepare('SELECT *, GROUP_CONCAT(`myokndefht_artists`.`name` SEPARATOR \', \') AS `artists` FROM `myokndefht_tracks` 
                                        INNER JOIN `myokndefht_artiststracks` ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists` ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
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
        $stmt = $this->prepare('SELECT *, GROUP_CONCAT(`myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName` FROM `myokndefht_tracks` 
                                        INNER JOIN `myokndefht_artiststracks` ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists` ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        WHERE 
                                            `hash` = :hash
                                        GROUP BY
                                            `myokndefht_tracks`.`id`');
        $stmt->bindValue(':hash', $this->getHash(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * @return bool
     */
    public function incrementDownloadCount(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_tracks` SET `downloadCount` = `downloadCount` + 1 WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        return $stmt->execute();
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
        return $stmt->fetch()->tracksCount;
    }
}
<?php


namespace App\Models;


use App\Models\Scheme\DownloadListsTracksScheme;
use PDO;

class DownloadListTracks extends DownloadListsTracksScheme
{
    /**
     * Retourne les musique de la liste de téléchargement
     *
     * @return Tracks[]|false
     */
    public function getTracks(): array|false
    {
        $stmt = $this->prepare('SELECT 
                                        `myokndefht_tracks`.`id`,
                                        `title`, `bpm`, `bitrate`, `releaseDate`, `path`, `hash`, `isPending`, `listenCount`, `id_musicKey`, `id_categories`,
                                        GROUP_CONCAT(DISTINCT `myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName`,
                                        COUNT(DISTINCT `myokndefht_usersdownloadedtracks`.`id`) AS `downloadCount`,
                                        `myokndefht_musickey`.`musicKey`,
                                        `myokndefht_categories`.`id` AS `categoryId`, `myokndefht_categories`.`name` AS `categoryName`
                                    FROM
                                        `myokndefht_downloadliststracks`
                                        INNER JOIN `myokndefht_tracks`
                                                   ON `myokndefht_downloadliststracks`.`id_tracks` = `myokndefht_tracks`.`id`
                                        INNER JOIN `myokndefht_artiststracks`
                                                   ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists`
                                                   ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        LEFT JOIN `myokndefht_usersdownloadedtracks`
                                                  ON `myokndefht_tracks`.`id` = `myokndefht_usersdownloadedtracks`.`id_tracks`
                                        INNER JOIN `myokndefht_musickey`
                                                   ON `myokndefht_tracks`.`id_musicKey` = `myokndefht_musickey`.`id`
                                        INNER JOIN `myokndefht_categories`
                                                   ON `myokndefht_tracks`.`id_categories` = `myokndefht_categories`.`id`
                                    WHERE
                                        `id_usersDownloadLists` = :idDownloadList
                                    GROUP BY
                                        `myokndefht_tracks`.`id`');
        $stmt->bindValue(':idDownloadList', $this->getIdUsersDownloadLists(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Tracks::class);
    }

    /**
     * Ajoute une musique dans une liste de téléchargement via l'id de la liste et l'id de la musique
     *
     * @return bool
     */
    public function addTrackToDownloadListByIdTrackAndIdCategory(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_downloadliststracks` SET `id_tracks` = :idTrack, `id_usersDownloadLists` = :idDownloadList');
        $stmt->bindValue(':idTrack', $this->getIdTracks(), PDO::PARAM_INT);
        $stmt->bindValue(':idDownloadList', $this->getIdUsersDownloadLists(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Vérifie si une musique est déjà dans la liste de lecture
     *
     * @return bool
     */
    public function checkTrackIsAlreadyInDownloadList(): bool
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `isExist` FROM `myokndefht_downloadliststracks` WHERE `id_tracks` = :idTrack AND `id_usersDownloadLists` = :idDownloadList');
        $stmt->bindValue(':idTrack', $this->getIdTracks(), PDO::PARAM_INT);
        $stmt->bindValue(':idDownloadList', $this->getIdUsersDownloadLists(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()->isExist;
    }

    /**
     * Retourne le nombre de musique dans la liste de téléchargement
     */
    public function countNumberOfTrackInDownloadList(): int
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `numberOfTracks` FROM `myokndefht_downloadliststracks` WHERE `id_usersDownloadLists` = :idDownloadList');
        $stmt->bindValue(':idDownloadList', $this->getIdUsersDownloadLists(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch()->numberOfTracks;
    }
}
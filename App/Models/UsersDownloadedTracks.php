<?php


namespace App\Models;


use App\Models\Scheme\UsersDownloadedTracksScheme;
use PDO;

class UsersDownloadedTracks extends UsersDownloadedTracksScheme
{
    /**
     * Indique si une musique à déjà été télécharger par l'utilisateur
     *
     * @return bool
     */
    public function userAlreadyDownloadTrackById(): bool
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `isAlreadyDownloaded` FROM `myokndefht_usersdownloadedtracks` WHERE `id_tracks` = :idTrack AND `id_users` = :idUser');
        $stmt->bindValue(':idTrack', $this->getIdTracks(), PDO::PARAM_INT);
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetch()->isAlreadyDownloaded;
    }

    /**
     * Ajoute la musique télécharger à la liste des musique déjà télécharger par l'utilisateur
     *
     * @return bool
     */
    public function addTrackToDownloaded(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_usersdownloadedtracks` (`id_users`, `id_tracks`) VALUES (:idUser, :idTrack)');
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        $stmt->bindValue(':idTrack', $this->getIdTracks(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
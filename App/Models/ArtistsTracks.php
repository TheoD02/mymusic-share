<?php


namespace App\Models;


use App\Models\Scheme\ArtistsTracksScheme;
use PDO;

class ArtistsTracks extends ArtistsTracksScheme
{

    /**
     * Associe l'artiste à une musique via leurs ID
     *
     * @return bool
     */
    public function associateArtistToTrack(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_artiststracks` (`id_artists`, `id_tracks`) VALUES (:idArtist, :idTrack)');
        $stmt->bindValue(':idArtist', $this->getIdArtists(), PDO::PARAM_INT);
        $stmt->bindValue(':idTrack', $this->getIdTracks(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Dissocie un artiste d'une musique
     *
     * @return bool
     */
    public function dissociateArtistFromTrackById(): bool
    {
        $stmt = $this->prepare('DELETE FROM `myokndefht_artiststracks` WHERE `id_tracks` = :idTrack AND `id_artists` = :idArtist');
        $stmt->bindValue(':idTrack', $this->getIdTracks(), PDO::PARAM_INT);
        $stmt->bindValue(':idArtist', $this->getIdArtists(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
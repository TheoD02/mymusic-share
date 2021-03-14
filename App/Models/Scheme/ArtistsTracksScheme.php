<?php


namespace App\Models\Scheme;


use App\Models\Artists;
use App\Models\Tracks;
use Core\Base\BaseModel;
use PDO;

class ArtistsTracksScheme extends BaseModel
{
    protected int $id;
    protected int $id_artists;
    protected int $id_tracks;

    private static ?Artists $artist = null;
    private static ?Tracks $track = null;

    /**
     * Retourne les variables de l'objet
     *
     * @return array
     */
    public function getModelVars(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdArtists(): int
    {
        return $this->id_artists;
    }

    /**
     * @param int $id_artists
     *
     * @return self
     */
    public function setIdArtists(int $id_artists): self
    {
        $this->id_artists = $id_artists;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdTracks(): int
    {
        return $this->id_tracks;
    }

    /**
     * @param int $id_tracks
     *
     * @return self
     */
    public function setIdTracks(int $id_tracks): self
    {
        $this->id_tracks = $id_tracks;
        return $this;
    }

    /**
     * @return Artists|false
     */
    public function getArtist(): Artists|false
    {
        if (self::$artist === null || self::$artist->getId() !== $this->getIdArtists())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_artists` WHERE `id` = :idArtist');
            $stmt->bindValue(':idArtist', $this->getIdArtists(), PDO::PARAM_INT);
            $stmt->execute();
            self::$artist = $stmt->fetchObject(Artists::class);
        }
        return self::$artist;
    }

    /**
     * @return Tracks|false
     */
    public function getTrack(): Tracks|false
    {
        if (self::$track === null || self::$track->getId() !== $this->getIdTracks())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_tracks` WHERE `id` = :idTrack');
            $stmt->bindValue(':idTrack', $this->getIdTracks(), PDO::PARAM_INT);
            $stmt->execute();
            self::$track = $stmt->fetchObject(Artists::class);
        }
        return self::$track;
    }
}
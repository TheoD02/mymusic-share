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
}
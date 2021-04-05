<?php


namespace App\Models\Scheme;


use App\Models\Tracks;
use App\Models\Users;
use Core\Base\BaseModel;
use PDO;

class UsersDownloadedTracksScheme extends BaseModel
{
    public int $id;
    public int $id_tracks;
    public int $id_users;
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
     * @return UsersDownloadedTracksScheme
     */
    public function setId(int $id): UsersDownloadedTracksScheme
    {
        $this->id = $id;
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
     * @return UsersDownloadedTracksScheme
     */
    public function setIdTracks(int $id_tracks): UsersDownloadedTracksScheme
    {
        $this->id_tracks = $id_tracks;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUsers(): int
    {
        return $this->id_users;
    }

    /**
     * @param int $id_users
     *
     * @return UsersDownloadedTracksScheme
     */
    public function setIdUsers(int $id_users): UsersDownloadedTracksScheme
    {
        $this->id_users = $id_users;
        return $this;
    }
}
<?php


namespace App\Models\Scheme;


use App\Models\Tracks;
use App\Models\UsersDownloadLists;
use Core\Base\BaseModel;
use PDO;

class DownloadListsTracksScheme extends BaseModel
{
    protected int $id;
    protected int $id_tracks;
    protected int $id_usersDownloadLists;

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
     * @return int
     */
    public function getIdUsersDownloadLists(): int
    {
        return $this->id_usersDownloadLists;
    }

    /**
     * @param int $id_usersDownloadLists
     *
     * @return self
     */
    public function setIdUsersDownloadLists(int $id_usersDownloadLists): self
    {
        $this->id_usersDownloadLists = $id_usersDownloadLists;
        return $this;
    }
}
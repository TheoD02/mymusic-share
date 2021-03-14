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

    private static ?Tracks $track = null;
    private static ?UsersDownloadLists $userDownloadList = null;

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

    /**
     * @return Tracks|false
     */
    public function getTrack(): Tracks|false
    {
        if (self::$track === null || self::$track->getId() !== $this->getIdTracks())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_tracks` WHERE `id` = :idTrack');
            $stmt->bindValue(':idTrack', $this->getIdUsersDownloadLists(), PDO::PARAM_INT);
            $stmt->execute();
            self::$track = $stmt->fetchObject(Tracks::class);
        }
        return self::$track;
    }


    /**
     * @return UsersDownloadLists|false
     */
    public function getUserDownloadList(): UsersDownloadLists|false
    {
        if (self::$userDownloadList === null || self::$userDownloadList->getId() !== $this->getIdUsersDownloadLists())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_usersdownloadlists` WHERE `id` = :idUserDownloadList');
            $stmt->bindValue(':idUserDownloadList', $this->getIdUsersDownloadLists(), PDO::PARAM_INT);
            $stmt->execute();
            self::$userDownloadList = $stmt->fetchObject(UsersDownloadLists::class);
        }
        return self::$userDownloadList;
    }

}
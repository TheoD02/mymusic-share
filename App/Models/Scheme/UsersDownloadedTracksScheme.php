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

    private static ?Users $user = null;
    private static ?Tracks $track = null;

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

    /**
     * @return Tracks|false
     */
    public function getTrack(): Tracks|false
    {
        if (self::$track === null || self::$track->getId() !== $this->getIdTracks())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_tracks` WHERE `id` = :idTrack');
            $stmt->bindValue(':idTrack', $this->getIdUsers(), PDO::PARAM_INT);
            $stmt->execute();
            self::$track = $stmt->fetchObject(Tracks::class);
        }
        return self::$track;
    }

    /**
     * @return Tracks|false
     */
    public function getUser(): Tracks|false
    {
        if (self::$user === null || self::$user->getId() !== $this->getIdUsers())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_users` WHERE `id` = :idUser');
            $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
            $stmt->execute();
            self::$user = $stmt->fetchObject(Tracks::class);
        }
        return self::$user;
    }
}
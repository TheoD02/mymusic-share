<?php


namespace App\Models\Scheme;


use App\Models\Artists;
use App\Models\Categories;
use App\Models\MusicKey;
use Core\Base\BaseModel;
use DateTime;
use PDO;

class TracksScheme extends BaseModel
{
    protected int $id;
    protected string $title;
    protected int $bpm;
    protected int $bitrate;
    protected string $releaseDate;
    protected string $path;
    protected string $hash;
    protected bool $isPending;
    protected int $id_categories;
    protected int $id_musicKey;
    protected int $listenCount;
    protected int $downloadCount;

    protected string $artistsName;

    private static ?Categories $category = null;
    private static ?MusicKey $musicKey = null;

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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return int
     */
    public function getBpm(): int
    {
        return $this->bpm;
    }

    /**
     * @param int $bpm
     *
     * @return self
     */
    public function setBpm(int $bpm): self
    {
        $this->bpm = $bpm;
        return $this;
    }

    /**
     * @return int
     */
    public function getBitrate(): int
    {
        return $this->bitrate;
    }

    /**
     * @param int $bitrate
     *
     * @return self
     */
    public function setBitrate(int $bitrate): self
    {
        $this->bitrate = $bitrate;
        return $this;
    }

    public function getFormattedReleaseDate(): string
    {
        return (new DateTime($this->releaseDate))->format('d/m/Y');
    }

    /**
     * @return string
     */
    public function getReleaseDate(): string
    {
        return $this->releaseDate;
    }

    /**
     * @param string $releaseDate
     *
     * @return self
     */
    public function setReleaseDate(string $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return self
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return self
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->isPending;
    }

    /**
     * @param bool $isPending
     *
     * @return self
     */
    public function setIsPending(bool $isPending): self
    {
        $this->isPending = $isPending;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdCategories(): int
    {
        return $this->id_categories;
    }

    /**
     * @param int $id_categories
     *
     * @return self
     */
    public function setIdCategories(int $id_categories): self
    {
        $this->id_categories = $id_categories;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdMusicKey(): int
    {
        return $this->id_musicKey;
    }

    /**
     * @param int $id_musicKey
     *
     * @return self
     */
    public function setIdMusicKey(int $id_musicKey): self
    {
        $this->id_musicKey = $id_musicKey;
        return $this;
    }

    /**
     * @return int
     */
    public function getListenCount(): int
    {
        return $this->listenCount;
    }

    /**
     * @param int $listenCount
     *
     * @return self
     */
    public function setListenCount(int $listenCount): self
    {
        $this->listenCount = $listenCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getDownloadCount(): int
    {
        return $this->downloadCount;
    }

    /**
     * @param int $downloadCount
     *
     * @return self
     */
    public function setDownloadCount(int $downloadCount): self
    {
        $this->downloadCount = $downloadCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getArtistsName(): string
    {
        return $this->artistsName;
    }

    /**
     * @param string $artistsName
     *
     * @return self
     */
    public function setArtistsName(string $artistsName): self
    {
        $this->artistsName = $artistsName;
        return $this;
    }

    /**
     * @return Categories|false
     */
    public function getCategory(): Categories|false
    {
        if (self::$category === null || self::$category->getId() !== $this->getIdCategories())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_categories` WHERE `id` = :idCategory');
            $stmt->bindValue(':idCategory', $this->getIdCategories(), PDO::PARAM_INT);
            $stmt->execute();
            self::$category = $stmt->fetchObject(Categories::class);
        }
        return self::$category;
    }

    /**
     * @return MusicKey|false
     */
    public function getMusicKey(): MusicKey|false
    {
        if (self::$musicKey === null || self::$musicKey->getId() !== $this->getIdMusicKey())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_musickey` WHERE `id` = :idMusicKey');
            $stmt->bindValue(':idMusicKey', $this->getIdMusicKey(), PDO::PARAM_INT);
            $stmt->execute();
            self::$musicKey = $stmt->fetchObject(MusicKey::class);
        }
        return self::$musicKey;
    }
}
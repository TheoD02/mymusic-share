<?php


namespace App\Models\Scheme;


use App\Models\Tracks;
use Core\Base\BaseModel;
use PDO;

class CategoriesScheme extends BaseModel
{
    protected int $id;
    protected string $name;
    protected string $slug;
    protected string $imgPath;

    private static array|false|null $track = null;

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return string
     */
    public function getImgPath(): string
    {
        return $this->imgPath;
    }

    /**
     * @param string $imgPath
     *
     * @return self
     */
    public function setImgPath(string $imgPath): self
    {
        $this->imgPath = $imgPath;
        return $this;
    }

    /**
     * @return Tracks[]|false
     */
    public function getTracks(): array|false
    {
        if (self::$track === null|| self::$track->getIdCategories() !== $this->getId())
        {
            $stmt = $this->prepare('SELECT *, GROUP_CONCAT(`myokndefht_artists`.`name` SEPARATOR \', \') AS `artistsName` FROM `myokndefht_tracks` 
                                        INNER JOIN `myokndefht_artiststracks` ON `myokndefht_tracks`.`id` = `myokndefht_artiststracks`.`id_tracks`
                                        INNER JOIN `myokndefht_artists` ON `myokndefht_artiststracks`.`id_artists` = `myokndefht_artists`.`id`
                                        WHERE 
                                            `id_categories` = :idCategory AND `isPending` = 0
                                        GROUP BY
                                            `myokndefht_tracks`.`id`');
            $stmt->bindValue(':idCategory', $this->getId(), PDO::PARAM_INT);
            $stmt->execute();
            self::$track = $stmt->fetchAll(PDO::FETCH_CLASS, Tracks::class);
        }
        return self::$track;
    }
}
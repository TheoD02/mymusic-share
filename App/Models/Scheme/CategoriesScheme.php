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
}
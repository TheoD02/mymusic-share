<?php


namespace App\Models\Scheme;


use Core\Base\BaseModel;

class MusicKeyScheme extends BaseModel
{
    protected int $id;
    protected string $musicKey;

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
    public function getMusicKey(): string
    {
        return $this->musicKey;
    }

    /**
     * @param string $musicKey
     *
     * @return self
     */
    public function setMusicKey(string $musicKey): self
    {
        $this->musicKey = $musicKey;
        return $this;
    }
}
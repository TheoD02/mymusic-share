<?php


namespace App\Models\Scheme;

use App\Models\DownloadListTracks;
use App\Models\Users;
use Core\Base\BaseModel;
use PDO;

class UsersDownloadListsScheme extends BaseModel
{
    protected int $id;
    protected string $name;
    protected int $id_users;

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
     * @return int
     */
    public function getIdUsers(): int
    {
        return $this->id_users;
    }

    /**
     * @param int $id_users
     *
     * @return UsersDownloadListsScheme
     */
    public function setIdUsers(int $id_users): UsersDownloadListsScheme
    {
        $this->id_users = $id_users;
        return $this;
    }
}
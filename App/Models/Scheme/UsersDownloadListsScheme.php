<?php


namespace App\Models\Scheme;

use App\Models\Subscriptions;
use App\Models\Users;
use Core\Base\BaseModel;
use PDO;

class UsersDownloadListsScheme extends BaseModel
{
    protected int $id;
    protected string $name;
    protected int $id_users;

    private static ?Users $user = null;

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
     * @return self
     */
    public function setIdUsers(int $id_users): self
    {
        $this->id_users = $id_users;
        return $this;
    }

    /**
     * @return Users|false
     */
    public function getUser(): Users|false
    {
        if (self::$user === null || self::$user->getId() !== $this->getIdUsers())
        {
            $stmt = $this->prepare('SELECT * FROM `myokndefht_users` WHERE `id` = :idUser');
            $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
            $stmt->execute();
            self::$user = $stmt->fetchObject(Subscriptions::class);
        }
        return self::$user;
    }
}
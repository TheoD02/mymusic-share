<?php


namespace App\Models;


use App\Models\Scheme\UsersDownloadListsScheme;
use PDO;

class UsersDownloadLists extends UsersDownloadListsScheme
{
    /**
     * Créer une liste de lecture associé à un utilisateur via son ID
     *
     * @return bool
     */
    public function addDownloadList(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_usersdownloadlists` (`name`, `id_users`) VALUES (:name, :idUser)');
        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Vérifie si une catégorie avec le meme nom existe déjà via l'id de l'utilisateur et le nom de la categorie
     *
     * @return bool
     */
    public function checkDownloadListNameIsFreeByNameAndUserID(): bool
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `isExist` FROM `myokndefht_usersdownloadlists` WHERE `id_users` = :idUser AND `name` = :name');
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch()->isExist;
    }

    /**
     * Retourne les liste de téléchargement de l'utilisateur
     *
     * @return array|false
     */
    public function getUserDownloadsListByUserId(): array|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_usersdownloadlists` WHERE `id_users` = :idUser');
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retourne la première liste de téléchargement de l'utilisateur
     *
     * @return UsersDownloadLists|false
     */
    public function getFirstUserDownloadList(): UsersDownloadLists|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_usersdownloadlists` WHERE `id_users` = :idUser ORDER BY `id` ASC');
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Retourne la liste de téléchargement de l'utilisateur via son id et l'id de l'utilisateur
     *
     * @return UsersDownloadLists|false
     */
    public function getUserDownloadListByIdAndUserId(): UsersDownloadLists|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_usersdownloadlists` WHERE `id` = :id AND `id_users` = :idUser');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    public function deleteDownloadListById(): bool
    {
        $stmt = $this->prepare('DELETE FROM `myokndefht_usersdownloadlists` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
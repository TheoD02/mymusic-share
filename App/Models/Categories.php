<?php


namespace App\Models;


use App\Models\Scheme\CategoriesScheme;
use PDO;

class Categories extends CategoriesScheme
{
    /**
     * Ajoute une catégorie
     *
     * @return bool
     */
    public function addCategory(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_categories` (`name`, `slug`, `imgPath`) VALUES (:name, :slug, :imgPath)');
        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':slug', $this->getSlug(), PDO::PARAM_STR);
        $stmt->bindValue(':imgPath', $this->getImgPath(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Retourne une catégorie via son ID
     *
     * @return Categories|false
     */
    public function getCategoryById(): Categories|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_categories` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Retourne la liste des catégories
     *
     * @return Categories[]|false
     */
    public function getCategoriesList(): array|false
    {
        $stmt = $this->query('SELECT * FROM `myokndefht_categories`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Vérifie si le nom d'une catégorie est disponible
     *
     * @return bool
     */
    public function checkCategoryNameIsFree(): bool
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `isExist` FROM `myokndefht_categories` WHERE `name` = :name');
        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch()->isExist;
    }

    /**
     * Vérifie si le slug pour une catégorie est disponible
     *
     * @return bool
     */
    public function checkCategorySlugIsFree(): bool
    {
        $stmt = $this->prepare('SELECT COUNT(`id`) AS `isExist` FROM `myokndefht_categories` WHERE `slug` = :slug');
        $stmt->bindValue(':slug', $this->getSlug(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch()->isExist;
    }

    /**
     * Supprime la catégorie via son ID
     *
     * @return bool
     */
    public function deleteCategoryById(): bool
    {
        $stmt = $this->prepare('DELETE FROM `myokndefht_categories` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Met à jour la catégorie via son ID
     *
     * @return bool
     */
    public function updateCategoryById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_categories` SET `name` = :name, `slug` = :slug WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':slug', $this->getSlug(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Retourne une catégorie via son SLUG
     *
     * @return Categories|false
     */
    public function getCategoryBySlug(): Categories|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_categories` WHERE `slug` = :slug');
        $stmt->bindValue(':slug', $this->getSlug(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }
}
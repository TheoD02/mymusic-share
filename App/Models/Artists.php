<?php


namespace App\Models;


use App\Models\Scheme\ArtistsScheme;
use PDO;

class Artists extends ArtistsScheme
{

    /**
     * Rechercher un artiste par son nom
     *
     * @return Artists|false
     */
    public function searchOneArtistByName(): Artists|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_artists` WHERE `name` = :name');
        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Ajoute un artiste
     *
     * @return bool
     */
    public function addArtist(): bool
    {
        $stmt = $this->prepare('INSERT INTO `myokndefht_artists` (`name`) VALUES (:artistName);');
        $stmt->bindValue(':artistName', $this->getName(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Retourne la liste des artistes
     *
     * @return Artists[]|false
     */
    public function getArtistList(): array|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_artists`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retourne les information d'un artiste via son ID
     *
     * @return Artists|false
     */
    public function getArtistById(): Artists|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_artists` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }

    /**
     * Met à jour un artiste
     *
     * @return bool
     */
    public function updateArtistById(): bool
    {
        $stmt = $this->prepare('UPDATE `myokndefht_artists` SET `name` = :name WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $this->getName(), PDO::PARAM_STR);
        return $stmt->execute();
    }

    /**
     * Supprime un artiste via son ID
     *
     * @return bool
     */
    public function deleteArtistById(): bool
    {
        $stmt = $this->prepare('DELETE FROM `myokndefht_artists` WHERE `id` = :id');
        $stmt->bindValue(':id', $this->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }
}
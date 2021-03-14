<?php


namespace App\Models;


use App\Models\Scheme\MusicKeyScheme;
use PDO;

class MusicKey extends MusicKeyScheme
{
    /**
     * Retourne la liste des clé harmonique
     *
     * @return MusicKey[]|false
     */
    public function getMusicKeyList(): array|false
    {
        $stmt = $this->query('SELECT * FROM `myokndefht_musickey`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}
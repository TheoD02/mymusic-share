<?php


namespace App\Models;


use App\Models\Scheme\UserRoleScheme;
use PDO;

class Roles extends UserRoleScheme
{


    /**
     * Retourne la liste des rôles
     *
     * @return Roles|false
     */
    public function getRolesList(): array|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_userrole`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }
}
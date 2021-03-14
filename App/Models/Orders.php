<?php


namespace App\Models;


use App\Models\Scheme\OrdersScheme;
use PDO;

class Orders extends OrdersScheme
{
    /**
     * Retourne la liste des commandes
     *
     * @return array|false
     */
    public function getOrdersList(): array|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_orders`');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Retourne l'abonnement de l'utilisateur actif si il y en a un.
     *
     * @return Orders|false
     */
    public function getCurrentSubscription(): Orders|false
    {
        $stmt = $this->prepare('SELECT * FROM `myokndefht_orders` WHERE `id_users` = :idUser AND `isActive` = 1');
        $stmt->bindValue(':idUser', $this->getIdUsers(), PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(self::class);
    }
}
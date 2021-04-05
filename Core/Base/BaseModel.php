<?php


namespace Core\Base;


use Core\Database;
use PDO;

class BaseModel
{
    private ?PDO $pdo = null;

    public function __construct()
    {
        /** Récupère l'instance de PDO (Singleton) */
        $this->pdo = Database::getPDOInstance();
    }

    /**
     * Retourne l'instance de PDO
     *
     * @return PDO
     */
    protected function getPDO()
    {
        return $this->pdo;
    }

    /**
     * Permet d'effectuer une requête via l'instance de PDO.
     *
     * @param string $query Requête SQL
     *
     * @return \PDOStatement
     */
    protected function query(string $query): \PDOStatement
    {
        return $this->pdo->query($query);
    }

    /**
     * Permet d'effectuer une requête préparer via l'instance de PDO.
     *
     * @param string $query Requête SQL
     *
     * @return \PDOStatement
     */
    protected function prepare(string $query): \PDOStatement
    {
        return $this->pdo->prepare($query);
    }

    /**
     * Retourne le dernier ID insérer en base de données
     *
     * @return int
     */
    public function getLastInsertId(): int
    {
        return (int)$this->pdo->lastInsertId();
    }
}

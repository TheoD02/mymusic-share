<?php


namespace Core;


use PDO;

class Database
{
    private static ?PDO $pdo = null;

    /**
     * Retourne l'instance de PDO
     *
     * @return PDO
     */
    public static function getPDOInstance(): PDO
    {
        /** Vérifie si une instance de PDO est déjà créer, si non la créer */
        if (self::$pdo === null)
        {
            self::createPDOInstance();
        }
        return self::$pdo;
    }

    /**
     * Créer l'instance de PDO
     */
    private static function createPDOInstance(): void
    {
        /** Instanciation de PDO, si une exception est générer on la capture et on arrête le script avec un message. */
        try
        {
            self::$pdo = new PDO('mysql:dbname=' . DB_NAME . ';host=' . DB_HOST . ';port=' . DB_PORT . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, DB_PARAMS);
        }
        catch (\Exception $e)
        {
            die($e->getMessage());
        }
    }
}
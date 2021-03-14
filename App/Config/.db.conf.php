<?php
/**
 * Identifiants base de données
 */
define('DB_USER', 'root');
define('DB_PASS', '');

/**
 * Informations serveur base de données
 */
define('DB_NAME', 'mymusic-share');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8');

/**
 * Options de PDO
 */
define('DB_PARAMS', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);
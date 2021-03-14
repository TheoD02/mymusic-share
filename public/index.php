<?php

/**
 * Chargement des variables d'environnements
 */
define('APP_ROOT', dirname(__DIR__, 1) . '/');

require APP_ROOT . 'App/Config/.env.php';

/** Gestionnaire d'erreur Whoops par */
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

/**
 * Chargement des routes
 */
require APP_ROOT . 'App/Routes/base.routes.php';
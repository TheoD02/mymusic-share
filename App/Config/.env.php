<?php
/** Démarrer la session */

use Core\CSRFHelper;

session_start();

/** Chargement de l'autoloader fournis via Composer */
require_once APP_ROOT . 'vendor/autoload.php';

/**
 * Chargement des informations de base de données
 */
require APP_ROOT . 'App/Config/.db.conf.php';

/**
 * Nom de page par défaut
 */
define('DEFAULT_PAGE_NAME', 'MyMusic Share');

/*
 * URL du site
 */
define('DEFAULT_URL', 'http://mymusic-share.local/');

/*
 * Nom de domaine
 */
define('DEFAULT_DOMAIN_NAME', 'mymusic-share.local');

require '.files.path.php';

(new CSRFHelper())->makeCsrfToken()->saveTokenInSession();
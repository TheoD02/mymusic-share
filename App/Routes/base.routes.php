<?php

use Core\Routing\Dispatcher;

$router = new AltoRouter();
$router->addMatchTypes(['slug' => '[a-zA-Z0-9\-]++']);

/** Namespace location */
define('NAMESPACE_FRONTOFFICE', 'FrontOffice\\');
define('NAMESPACE_BACKOFFICE', 'BackOffice\\');
define('NAMESPACE_AJAX', 'Ajax\\');

/**
 * Charge les routes
 */
require APP_ROOT . 'App/Routes/routes.loader.php';

/** Récupère l'url match ou rien si elle ne correspond à aucune route */
$match = $router->match();

// You can optionnally add a try/catch here to handle Exceptions
// Instanciate the dispatcher, give it the $match variable and a fallback action
$dispatcher = new Dispatcher($match, 'ErrorController::notFound');
// Setup Controllers' namespace
$dispatcher->setControllersNamespace('App\Controllers');
// then run the dispatch method which will call the mapped method
$dispatcher->dispatch();
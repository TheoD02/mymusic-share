<?php

namespace App\Controllers;

use Core\Base\BaseController;

class ErrorController extends BaseController
{
    /**
     * 404 Page non trouvée
     */
    public function notFound(): void
    {
        $this->render('ErrorPage', 'Erreur 404 - Page non trouvée', [
            'errorCode' => 404,
            'errorInfo' => 'La page que vous tenter d\'accéder n\'existe pas, ou pas encore',
        ]);
    }

    /**
     * 403 Interdit
     */
    public function forbidden(): void
    {
        $this->render('ErrorPage', 'Erreur 403 - Interdit', [
            'errorCode' => 403,
            'errorInfo' => 'Vous n\'avez pas le droit d\'accéder à cet page ou cet ressources',
        ]);
    }

    /**
     * 405 Méthode de requête non autorisée.
     */
    public function methodNotAllowed(): void
    {
        $this->render('ErrorPage', 'Erreur 405 - Méthode de requête non autorisée.', [
            'errorCode' => 405,
            'errorInfo' => 'Méthode de requête non autorisée',
        ]);
    }

    /**
     * 401 Non autorisé
     */
    public function unauthorized(): void
    {
        $this->render('ErrorPage', 'Erreur 401 - Non autorisé', [
            'errorCode' => 401,
            'errorInfo' => 'Une authentification est nécessaire pour accéder à la ressource.',
        ]);
    }
}
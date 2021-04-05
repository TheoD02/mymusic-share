<?php


namespace App\Controllers\FrontOffice\Authentification;


use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\UserAuthHelper;
use Core\UserHelper;

class LogoutController extends BaseController
{
    /**
     * LogoutController constructor.
     *
     * Vérifie que l'utilisateur soit connecté
     */
    public function __construct()
    {
        parent::__construct();
        if (!UserHelper::isAuthAsAnyRole())
        {
            FlashMessageService::addWarningMessage('Vous devais être connecté pour accéder à cet page !');
            $this->redirectWithAltoRouter('home');
        }
    }

    /**
     * Déconnecte l'utilisateur est supprime les données stockée en session et les cookies
     */
    public function logoutAction(): void
    {
        /** Détruire les données enregistrée en session */
        unset($_SESSION['user']);

        /** Supprimé le cookie "rememberMe" si il été existant */
        if (isset($_COOKIE['user_persist']))
        {
            setcookie('user_persist', '', -1);
        }
        FlashMessageService::addSuccessMessage('Vous avez été déconnecté avec succès');
        $this->redirectWithAltoRouter('home');
    }
}
<?php


namespace Core\Base;

use App\Models\Users;
use Core\FlashMessageService;

class BaseController
{
    public function __construct()
    {
        /**
         * Vérifie si l'utilisateur à un cookie enregistrer pour garder sa connexion active (rememberMe)
         * Et qu'il ne soit pas déjà authentifié
         */
        if (isset($_COOKIE['user_persist']) && !isset($_SESSION['user']))
        {
            if (strlen($_COOKIE['user_persist']) === 255)
            {
                /** Récupérer un utilisateur via son rememberToken */
                $userInfo = (new Users())->setRememberMeToken($_COOKIE['user_persist'])
                                         ->getUserByRememberMeToken();
                /** Si on a des informations sur cette utilisateur on le connecte automatiquement */
                if ($userInfo !== false)
                {
                    /** Stocker des informations utilisateur en session (isAuth, role, nom, prénom) */
                    $_SESSION['user']['id']        = $userInfo->getId();
                    $_SESSION['user']['role']      = $userInfo->getIdUserRole();
                    $_SESSION['user']['username']  = $userInfo->getUsername();
                    $_SESSION['user']['canDownload'] = $userInfo->getRemainingDownload() === null ? false : true;

                    FlashMessageService::addSuccessMessage('Vous êtes toujours connectée !');
                    $this->redirect(\AltoRouter::getRouterInstance()->generate('home'));
                }
            }
            else
            {
                setcookie('user_persist', '', -1);
            }
        }
    }

    /**
     * Rend une vue
     *
     * @param string $viewName     Name of view to load
     * @param string $title        title to pass to view (layout)
     * @param array  $params       parameters to pass to view
     * @param string $viewBasePath need CONSTANT eg. [View::FRONT_OFFICE_PATH] or
     *                             [View::BACK_OFFICE_PATH]
     *
     * @throws \Exception
     */
    public function render(string $viewName, string $title, array $params = [], string $viewBasePath = BaseView::FRONT_OFFICE_PATH): void
    {
        BaseView::render($viewName, $title, $params, $viewBasePath);
    }

    /**
     * Rediriger vers une page.
     *
     * @param string   $to           URL de la redirection
     * @param int|null $redirectCode Code de redirection HTTP
     */
    protected function redirect(string $to, int $redirectCode = null): void
    {
        header('Location: ' . $to, $redirectCode);
        exit();
    }

    /**
     * Rediriger vers une page en passant par le router.
     *
     * @param string   $routeName    Nom de la route
     * @param array    $params       Paramètre d'url à passer
     * @param int|null $redirectCode Code de redirection HTTP
     *
     */
    protected function redirectWithAltoRouter(string $routeName, array $params = [], int $redirectCode = null): void
    {
        try
        {
            header('Location: ' . \AltoRouter::getRouterInstance()
                                             ->generate($routeName, $params), $redirectCode);
            exit();
        }
        catch (\Exception $e)
        {
            die($e->getMessage());
        }

    }
}
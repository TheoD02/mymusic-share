<?php


namespace Core\Base;


class BaseView
{
    /**
     * Emplacement par défaut des dossiers contenant les vues
     */
    public const FRONT_OFFICE_PATH = APP_ROOT . 'App/Views/FrontOffice/';
    public const BACK_OFFICE_PATH  = APP_ROOT . 'App/Views/BackOffice/';

    /**
     * Emplacement par défaut du dossier contenant les layouts
     */
    private const LAYOUT_PATH = APP_ROOT . 'App/Views/layouts/';
    /**
     * Nom par défaut des layout
     */
    private const FRONT_LAYOUT_NAME = 'frontOfficeLayout';
    private const BACK_LAYOUT_NAME  = 'backOfficeLayout';

    /**
     * Rend une vue
     *
     * @param string $viewName     Nom de la vue a charger
     * @param string $title        Titre de la page
     * @param array  $params       Les variables à passer à la vue
     * @param string $viewBasePath need CONSTANT ex. [View::FRONT_OFFICE_PATH] ou [View::BACK_OFFICE_PATH]
     *
     * @throws \Exception
     */
    public static function render(string $viewName, string $title, array $params = [], string $viewBasePath = self::FRONT_OFFICE_PATH): void
    {
        self::verifyLayoutExisting($viewBasePath);

        $viewName     = ucfirst($viewName) . 'View.php';
        $viewFullPath = $viewBasePath . $viewName;

        if (!is_readable($viewFullPath))
        {
            throw new \RuntimeException('La vue [' . $viewName . '] n\'existe pas dans [' . $viewBasePath . ']');
        }

        $content = self::generateViewContent($viewFullPath, $params);
        self::generateLayoutWithContent($viewBasePath, $title, $content);
    }

    /**
     * Vérifie que l'utilisateur à bien passer une constante de la classe et que le layout existe bien
     *
     * @param string $viewBasePath
     *
     * @throws \Exception
     */
    private static function verifyLayoutExisting(string $viewBasePath): void
    {
        if ($viewBasePath === self::FRONT_OFFICE_PATH)
        {
            if (!is_readable(self::LAYOUT_PATH . self::FRONT_LAYOUT_NAME . '.php'))
            {
                throw new \Exception('Le layout [' . self::FRONT_LAYOUT_NAME . '.php] n\'existe pas dans [' . self::LAYOUT_PATH . ']');
            }
        }
        else if ($viewBasePath === self::BACK_OFFICE_PATH)
        {
            if (!is_readable(self::LAYOUT_PATH . self::BACK_LAYOUT_NAME . '.php'))
            {
                throw new \Exception('Le layout [' . self::BACK_LAYOUT_NAME . '.php] n\'existe pas dans [' . self::LAYOUT_PATH . ']');
            }
        }
        else
        {
            throw new \Exception('Veuillez définir le type de template à chargée. eg. [View::FRONT_OFFICE_PATH] or [View::BACK_OFFICE_PATH]');
        }
    }

    /**
     * Retourne le contenu de la page générer
     *
     * @param string $viewFullPath
     * @param array  $params
     *
     * @return string
     */
    private static function generateViewContent(string $viewFullPath, array $params = []): string
    {
        ob_start();
        if ($params !== [])
        {
            extract($params, EXTR_OVERWRITE);
        }

        /**
         * On démarre une memoire tampon,
         * on charge la vue puis on récupère le contenu générer dans la mémoire tampon
         * et on supprime le contenu qui à était générer
         */

        require $viewFullPath;
        return ob_get_clean();
    }

    /**
     * Génère la vue et son contenu
     *
     * @param string $viewPath Chemin d'accès au fichier de la vue
     * @param string $title    Titre de la page
     * @param string $content  Contenu de la page
     */
    private static function generateLayoutWithContent(string $viewPath, string $title, string $content): void
    {
        if ($viewPath === self::FRONT_OFFICE_PATH)
        {
            require self::LAYOUT_PATH . self::FRONT_LAYOUT_NAME . '.php';
        }
        else if ($viewPath === self::BACK_OFFICE_PATH)
        {
            require self::LAYOUT_PATH . self::BACK_LAYOUT_NAME . '.php';
        }
    }
}
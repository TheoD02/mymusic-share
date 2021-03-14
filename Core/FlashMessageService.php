<?php


namespace Core;


/**
 * Class FlashMessageService
 *
 * Permet de gérer les Flash Message pour afficher un message de confirmation, d'avertissement ou
 * d'erreur à l'utilisateur et qu'une fois afficher il soit supprimer.
 *
 * @package Core
 */
class FlashMessageService
{
    /** @var string Nom du tableau stockée en session pour les FlashMessage */
    private const DEFAULT_FlASH_MESSAGE_NAME = 'flashMessage';

    /**
     * Ajoute un message de succès en session
     *
     * @param string $value
     */
    public static function addSuccessMessage(string $value): void
    {
        $_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['success'][] = $value;
    }

    /**
     * Ajoute un message de d'erreur en session
     *
     * @param string $value
     */
    public static function addErrorMessage(string $value): void
    {
        $_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['error'][] = $value;
    }

    /**
     * Ajoute un message de d'avertissement en session
     *
     * @param string $value
     */
    public static function addWarningMessage(string $value): void
    {
        $_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['warning'][] = $value;
    }

    /**
     * Retourne les messages de succès, si il y en a. Si non null.
     *
     * @return array|null
     */
    public static function getSuccessMessages(): array|null
    {
        $messages = $_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['success'] ?? null;
        if ($messages !== null)
        {
            unset($_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['success']);
        }
        return $messages;
    }

    /**
     * Retourne les messages de d'erreur, si il y en a. Si non null.
     *
     * @return array|null
     */
    public static function getErrorMessages(): array|null
    {
        $messages = $_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['error'] ?? null;
        if ($messages !== null)
        {
            unset($_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['error']);
        }
        return $messages;
    }

    /**
     * Retourne les messages d'avertissement, si il y en a. Si non null.
     *
     * @return array|null
     */
    public static function getWarningMessages(): array|null
    {
        $messages = $_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['warning'] ?? null;
        if ($messages !== null)
        {
            unset($_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]['warning']);
        }
        return $messages;
    }

    /**
     * Supprime les message du type passer en paramètre
     *
     * @param string $type Type [success|warning|error]
     *
     * @return void
     */
    private static function removeMessage(string $type): void
    {
        $messages = $_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME][$type] ?? null;
        if ($messages !== null)
        {
            unset($_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME][$type]);
        }
    }

    /**
     * Supprime tout les messages stockée
     *
     * @return void
     */
    public static function removeAllMessages(): void
    {
        unset($_SESSION[self::DEFAULT_FlASH_MESSAGE_NAME]);
    }

    /**
     * Affiche tout les messages au format HTML
     *
     * @return string
     */
    public static function showAllMessages(): string
    {
        $html = self::showErrorMessages() ?? '';
        $html .= self::showWarningMessages() ?? '';
        $html .= self::showSuccessMessages() ?? '';
        return $html;
    }

    /**
     * Affiche les messages de succès au format HTML
     *
     * @return string|null
     */
    public static function showSuccessMessages(): string|null
    {
        $successMessages = self::getSuccessMessages();
        if ($successMessages !== null)
        {
            $html = '<ul class="my-0 success">';
            foreach ($successMessages as $message)
            {
                $html .= '<li>' . $message . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
        return null;
    }

    /**
     * Affiche les messages d'erreur au format HTML
     *
     * @return string|null
     */
    public static function showErrorMessages(): string|null
    {
        $errorMessages = self::getErrorMessages();
        if ($errorMessages !== null)
        {
            $html = '<ul class="my-0 error">';
            foreach ($errorMessages as $message)
            {
                $html .= '<li>' . $message . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
        return null;
    }

    /**
     * Affiche les messages d'avertissement au format HTML
     *
     * @return string|null
     */
    public static function showWarningMessages(): string|null
    {
        $warningMessages = self::getWarningMessages();
        if ($warningMessages !== null)
        {
            $html = '<ul class="my-0 warning">';
            foreach ($warningMessages as $message)
            {
                $html .= '<li>' . $message . '</li>';
            }
            $html .= '</ul>';
            return $html;
        }
        return null;
    }

    public static function oneErrorExistAtLeast()
    {
        return !empty($_SESSION['flashMessage']['success']) || !empty($_SESSION['flashMessage']['warning']) || !empty($_SESSION['flashMessage']['error']);
    }
}
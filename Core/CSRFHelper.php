<?php


namespace Core;


class CSRFHelper
{
    private const CSRF_SECURITY_SESSION_NAME = 'security';
    private const CSRF_NAME                  = 'CSRF';
    private ?string $csrfToken = null;

    /**
     * Retourne l'input avec le Token CSRF
     *
     * @return string
     * @throws \Exception
     */
    public static function generateCsrfHiddenInput(): string
    {
        return '<input type="hidden" name="csrf-token" value="' . self::getCsrfToken() . '" />';
    }

    /**
     * Retourne le token CSRF ou génère une erreur si il n'existe pas.
     *
     * @return string|null
     * @throws \Exception
     */
    public static function getCsrfToken(): ?string
    {
        if (isset($_SESSION[self::CSRF_SECURITY_SESSION_NAME][self::CSRF_NAME]))
        {
            return $_SESSION[self::CSRF_SECURITY_SESSION_NAME][self::CSRF_NAME];
        }
        throw new \Exception('Pas de token CSRF en Session');
    }

    /**
     * Créer le token CSRF
     *
     * @return mixed
     * @throws \Exception
     */
    public function makeCsrfToken(): self
    {
        $this->csrfToken = Security::generateToken(60);
        return $this;
    }

    /**
     * Sauvegarde le Token CSRF en Session
     *
     * @return $this
     */
    public function saveTokenInSession(): self
    {
        if ($this->csrfToken !== null && !isset($_SESSION[self::CSRF_SECURITY_SESSION_NAME][self::CSRF_NAME]))
        {
            $_SESSION[self::CSRF_SECURITY_SESSION_NAME][self::CSRF_NAME] = $this->csrfToken;
        }
        return $this;
    }
}
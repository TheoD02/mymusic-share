<?php


namespace Core;


class UserHelper
{
    private const AdminRole = 1;
    private const UserRole  = 2;

    /**
     * Retourne l'id de l'utilisateur
     *
     * @return int|null
     */
    public static function getUserID(): int|null
    {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Si l'utilisateur est authentifié peu importe son rôle.
     *
     * @return bool
     */
    public static function isAuthAsAnyRole(): bool
    {
        return self::isAuthAs(null);
    }

    /**
     * Retourne true si l'utilisateur et authentifié, et avec le role demander en paramètre
     *
     * @param int|null $role Role disponible avec les constante de classe [e.g AdminRole|UserRole]
     *                       Null si pas besoin de vérifié le role mais juste l'authentification
     *
     * @return bool
     */
    private static function isAuthAs(?int $role): bool
    {
        /** Si l'utilisateur est connectée, mais que aucun rôle n'est demander précisément */
        if (isset($_SESSION['user']) && $role === null)
        {
            return true;
        }

        /** Si l'utilisateur est connectée et qu'il doit avoir un rôle précis */
        if (isset($_SESSION['user']))
        {
            if ($role !== null && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === $role)
            {
                return true;
            }
        }
        return false;
    }

    /**
     * Si l'utilisateur est authentifié et son rôle est "user".
     *
     * @return bool
     */
    public static function isAuthAsUser(): bool
    {
        return self::isAuthAs(self::UserRole);
    }

    /**
     * Si l'utilisateur est authentifié et son rôle est "admin".
     *
     * @return bool
     */
    public static function isAuthAsAdmin(): bool
    {
        return self::isAuthAs(self::AdminRole);
    }

    /** Définir si l'utilisateur à le droit de télécharger
     *
     * @param bool $canDownload
     */
    public static function setIsAuthorizedToDownload(bool $canDownload): void
    {
        $_SESSION['user']['canDownload'] = $canDownload;
    }

    /** Retourne le statut si l'utilisateur à le droit de télécharger */
    public static function isAuthorizedToDownload(): bool
    {
        return $_SESSION['user']['canDownload'] ?? false;
    }
}
<?php


namespace Core;


class SessionManager
{
    /**
     * Définir un valeur en session
     *
     * @param string $key
     * @param string $value
     */
    public static function set(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Récupère une valeur défini en session
     *
     * @param string $key
     *
     * @return mixed retourne la valeur stockée ou null si elle n'existe pas
     */
    public static function get(string $key): mixed
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * Nettoie la session en supprimant toute les données.
     *
     * @return bool True si bien réinitialiser si non false
     */
    public static function clear(): bool
    {
        return session_reset();
    }

    /**
     * Régénérer l'ID de la sessions
     *
     * @return bool True si bien régénérer si non false
     */
    public static function regenerateID(): bool
    {
        return session_regenerate_id();
    }

    /**
     * Détruit la session
     *
     * @return bool True si bien détruit si non false
     */
    public static function destroy(): bool
    {
        return session_destroy();
    }
}
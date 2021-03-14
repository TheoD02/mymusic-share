<?php


namespace Core;


class Security
{
    /**
     * Génère un token aléatoire
     *
     * @param int $length Longueur du token à générer
     *
     * @return string
     */
    public static function generateToken(int $length): string
    {
        return bin2hex(random_bytes($length / 2));
    }
}
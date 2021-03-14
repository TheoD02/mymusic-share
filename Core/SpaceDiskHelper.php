<?php


namespace Core;


use AltoRouter;

class SpaceDiskHelper
{
    private const BASE = 1024;

    /**
     * Retourne la taille total du disque en Go
     */
    public static function getTotalSpace(): float
    {
        $bytes = disk_total_space('.');
        return round($bytes / 1024 / 1024 / 1024, 2);
    }

    /**
     * Retourne l'espace disponible en Go
     */
    public static function getFreeSpace(): float
    {
        $bytes = disk_free_space('.');
        return round($bytes / 1024 / 1024 / 1024, 2);
    }

    /**
     * Retourne l'espace utilisée en Go
     *
     * @return float
     */
    public static function getUsedSpace(): float
    {
        $freeSpace  = self::getFreeSpace();
        $totalSpace = self::getTotalSpace();
        return $totalSpace - $freeSpace;
    }

    public static function checkFreeSpace(float $sizeInGo, ?string $redirectRoute = null): void
    {
        if (self::getFreeSpace() < $sizeInGo)
        {
            FlashMessageService::addWarningMessage('La mise en ligne est actuellement restreinte en raison de manque d\'espace sur notre serveur, un signalement à été envoyé automatiquement à l\'administrateur.');
            $mailer = new Mailer();
            $mailer->setSubject('Report: FreeSpace Limit Reached')->setTo('theo.d02290@gmail.com')
                   ->sendMail('FreeSpaceLimitReached', null);
            if ($redirectRoute !== null)
            {
                header('Location: ' . AltoRouter::getRouterInstance()->generate($redirectRoute));
                exit;
            }
        }
    }
}
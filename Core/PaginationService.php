<?php


namespace Core;


class PaginationService
{
    private static ?int $totalNumberOfElements = null;
    private static ?int $numberOfElementsPerPage = null;
    private static ?int $totalPages = null;

    private static ?int $currentPage = null;

    private static int $numberOfElementBefore = 5;
    private static int $numberOfElementAfter = 5;

    private static ?int $numberOfPossiblePageBeforeCurrent = null;
    private static ?int $numberOfPossiblePageAfterCurrent = null;

    private static int $numberOfAdditionalPageToAddBefore = 0;
    private static int $numberOfAdditionalPageToAddAfter = 0;

    private static ?int $offsetStart = null;
    private static ?int $offsetEnd = null;

    public static function setTotalNumberOfElements(int $totalNumberOfElements): void
    {
        if ($totalNumberOfElements < 0)
        {
            throw new \Exception('Le nombre totale d\'éléments ne peu pas être inférieur à 0 !');
        }
        self::$totalNumberOfElements = $totalNumberOfElements;
    }

    public static function setNumberOfElementsPerPage(int $numberOfElementsPerPage): void
    {
        if ($numberOfElementsPerPage < 0)
        {
            throw new \Exception('Le nombre d\'éléments a afficher par page ne peu pas être inférieur à 0 !');
        }
        self::$numberOfElementsPerPage = $numberOfElementsPerPage;
    }

    public static function setCurrentPage(int $currentPage): void
    {
        if ($currentPage < 1)
        {
            throw new \Exception('La page courante ne peu pas être inférieur à 1 ! Veuillez définir une valeur supérieur à 1');
        }
        self::$currentPage = $currentPage;
    }

    public static function setNumberOfElementBefore(int $numberOfElementBefore): void
    {
        if ($numberOfElementBefore < 1)
        {
            throw new \Exception('Le nombre de page a afficher avant la page courante dans la pagination ne peu pas être inférieur à 1 ! Veuillez définir une valeur supérieur à 1');
        }
        self::$numberOfElementBefore = $numberOfElementBefore;
    }

    public static function setNumberOfElementAfter(int $numberOfElementAfter): void
    {
        if ($numberOfElementAfter < 1)
        {
            throw new \Exception('Le nombre de page a afficher avant la page courante dans la pagination ne peu pas être inférieur à 1 ! Veuillez définir une valeur supérieur à 1');
        }
        self::$numberOfElementAfter = $numberOfElementAfter;
    }

    public static function calculate(): void
    {
        self::calculateTotalNumberOfPages();
        self::calculateNumberOfPossiblePageBeforeCurrent();
        self::calculateNumberOfPossiblePageAfterCurrent();
        /** calcul le premier nombre de la pagination */
        self::$offsetStart = self::$numberOfPossiblePageBeforeCurrent - self::$numberOfAdditionalPageToAddAfter;
        if (self::$offsetStart < 1)
        {
            self::$offsetStart = 1;
        }
        /** calcul le dernier nombre de la pagination */
        self::$offsetEnd = self::$numberOfPossiblePageAfterCurrent + self::$numberOfAdditionalPageToAddBefore;
        if (self::$offsetEnd > self::$totalPages)
        {
            self::$offsetEnd = self::$totalPages;
        }
    }

    /**
     *  Retourne un tableau contenant les numéro des page de la pagination
     *
     * @return array
     */
    public static function getPagination(): array
    {
        $pagination = [];
        for ($i = self::getOffsetStart(); $i <= self::getOffsetEnd(); $i++)
        {
            if ($i === 1 || $i === self::getTotalPages()){
                continue;
            }
            $pagination[] = $i;
        }
        return $pagination;
    }

    /** Retourne l'offset pour la requête en base de données */
    public static function getOffsetForDB(): int
    {
        return self::$numberOfElementsPerPage * (self::$currentPage - 1);
    }

    /** Retourne la limit pour la requête en base de données */
    public static function getLimitForDB(): int
    {
        return self::$numberOfElementsPerPage;
    }

    /**
     * Calcul le nombre de page possible avant la page courante
     *
     * @throws \Exception
     */
    private static function calculateNumberOfPossiblePageBeforeCurrent(): void
    {
        if (self::$currentPage === null)
        {
            throw new \Exception('Veuillez définir la page courante ainsi que le nombre d\'éléments pouvant être afficher avant la page courante.' . PHP_EOL . 'Veuillez utiliser : PaginationService::setCurrentPage() et PaginationService::setNumberOfElementBefore()');
        }
        self::$numberOfPossiblePageBeforeCurrent = self::$currentPage - self::$numberOfElementBefore;
        if (self::$numberOfPossiblePageBeforeCurrent < 1)
        {
            self::$numberOfAdditionalPageToAddBefore = abs(self::$numberOfPossiblePageBeforeCurrent) + 1;
            self::$numberOfPossiblePageBeforeCurrent = 1;
        }
    }

    /**
     * Calcul le nombre de page possible après la page courante
     *
     * @throws \Exception
     */
    private static function calculateNumberOfPossiblePageAfterCurrent(): void
    {
        if (self::$currentPage === null)
        {
            throw new \Exception('Veuillez définir la page courante ainsi que le nombre d\'éléments pouvant être afficher avant la page courante.' . PHP_EOL . 'Veuillez utiliser : PaginationService::setCurrentPage() et PaginationService::setNumberOfElementAfter()');
        }
        self::$numberOfPossiblePageAfterCurrent = self::$currentPage + self::$numberOfElementAfter;
        if (self::$numberOfPossiblePageAfterCurrent > self::$totalPages)
        {
            self::$numberOfAdditionalPageToAddAfter = abs(self::$totalPages - self::$numberOfPossiblePageAfterCurrent);
            self::$numberOfPossiblePageAfterCurrent = self::$totalPages;
        }
    }

    /**
     * Calcule le nombre total de page
     *
     * @throws \Exception
     */
    private static function calculateTotalNumberOfPages(): void
    {
        if (self::$totalNumberOfElements === null || self::$numberOfElementsPerPage === null)
        {
            throw new \Exception('Veuillez définir le nombre total d\'éléments qui seront afficher ainsi que le nombre d\'éléments voulu par page.' . PHP_EOL . 'Veuillez utiliser : PaginationService::setTotalNumberOfElements() et PaginationService::setNumberOfElementsPerPage()');
        }
        else
        {
            self::$totalPages = (int)ceil(self::$totalNumberOfElements / self::$numberOfElementsPerPage);
        }
    }

    /**
     * @return int|null
     */
    public static function getCurrentPage(): ?int
    {
        return self::$currentPage;
    }

    /**
     * @return int|null
     */
    public static function getTotalPages(): ?int
    {
        return self::$totalPages;
    }

    /**
     * @return int|null
     */
    public static function getOffsetStart(): ?int
    {
        return self::$offsetStart;
    }

    /**
     * @return int|null
     */
    public static function getOffsetEnd(): ?int
    {
        return self::$offsetEnd;
    }
}
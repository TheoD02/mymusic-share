<?php


namespace App\Controllers\Ajax;


use App\Models\Categories;
use App\Models\Tracks;
use Core\FlashMessageService;
use Core\PaginationService;

class AjaxPlayerController
{
    /**
     * Ajoute une écoute à une musique via son hash
     *
     * @param string $hash
     */
    public function addListenOnTrack(string $hash): void
    {
        (new Tracks())->setHash($hash)->addListenOnTrackByHash();
    }

    /**
     * Récupérer le tableau de la liste des musique d'une catégorie via son slug et la page courante.
     *
     * @param string $slug
     * @param int    $currentPage
     *
     * @throws \Exception
     */
    public function getCategoryTrackList(string $slug, int $currentPage = 1): void
    {
        $trackModel = new Tracks();

        /** Récupérer la catégorie */
        $category = (new Categories())->setSlug($slug)
                                      ->getCategoryBySlug();

        /** Si une catégorie est bien trouvé */
        if ($category !== false)
        {
            /** On stock le nom de notre catégorie */
            $categoryName = $category->getName();
            /** Définir l'id de la catégorie pour rechercher les musique liée */
            $trackModel->setIdCategories($category->getId());

            /** Défini les données de pagination (Nombre total éléments, Nombre d'éléments par page, Numéro de page courante) */
            PaginationService::setTotalNumberOfElements($trackModel->getTotalNumberOfTracksInCategory());
            PaginationService::setNumberOfElementsPerPage(5);
            PaginationService::setCurrentPage($currentPage);
            PaginationService::calculate();

            /** Récupérer la liste des musique dans la catégorie précisé, avec l'offset et la limit calculer avec PaginationService */
            $tracksList = $trackModel->getTrackListByCategories(PaginationService::getOffsetForDB(), PaginationService::getLimitForDB());
        }
        /** Si aucune catégorie correspond à celle demander */
        else
        {
            $categoryName = 'Catégorie introuvable !';
            FlashMessageService::addErrorMessage('Catégorie introuvable.');
        }
        /** On rend la vue à envoyé a AJAX */
        require APP_ROOT . 'App/Views/FrontOffice/MusicTable.php';
    }
}
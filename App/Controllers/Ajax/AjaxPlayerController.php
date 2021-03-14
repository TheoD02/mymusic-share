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
     * Récupérer le tableau de la liste des musique d'une catégorie via son slug et le page courante.
     *
     * @param string $slug
     * @param int    $currentPage
     *
     * @throws \Exception
     */
    public function getCategory(string $slug, int $currentPage = 1)
    {
        $trackModel = new Tracks();

        $category = (new Categories())->setSlug($slug)
                                      ->getCategoryBySlug();
        if ($category !== false)
        {
            $categoryName = $category->getName();
            $trackModel->setIdCategories($category->getId());
            PaginationService::setTotalNumberOfElements($trackModel->getTotalNumberOfTracks());
            PaginationService::setNumberOfElementsPerPage(5);
            PaginationService::setCurrentPage($currentPage);
            PaginationService::calculate();
            $tracksList = $trackModel->getTrackListByCategories(PaginationService::getOffsetForDB(), PaginationService::getLimitForDB());
        }
        else
        {
            $categoryName = 'Catégorie introuvable !';
            FlashMessageService::addErrorMessage('Catégorie introuvable.');
        }
        ob_start();
        require APP_ROOT . 'App/Views/Ajax/CategoryTable.php';
        $tableContent = ob_get_clean();
        echo $tableContent;
    }
}
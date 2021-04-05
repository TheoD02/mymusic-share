<?php


namespace App\Controllers\FrontOffice\Categories;


use App\Models\Categories;
use Core\Base\BaseController;
use Core\PaginationService;

class CategoriesListController extends BaseController
{
    /**
     * Affiche la liste des catégorie
     *
     * @param int $pageNumber             Numéro de la page actuel
     * @param int $numberOfElementPerPage Nombre d'éléments à afficher par page
     *
     * @throws \Exception
     */
    public function showCategoriesList(int $pageNumber = 1, int $numberOfElementPerPage = 8): void
    {
        $categoryMdl = new Categories();

        /** Défini les données de pagination (Nombre total éléments, Nombre d'éléments par page, Numéro de page courante) */
        PaginationService::setTotalNumberOfElements($categoryMdl->getTotalNumberOfCategories());
        PaginationService::setNumberOfElementsPerPage($numberOfElementPerPage);
        PaginationService::setCurrentPage($pageNumber);
        PaginationService::calculate();

        if ($pageNumber > PaginationService::getTotalPages())
        {
            $this->redirectWithAltoRouter('categoriesList', ['pageNumber' => 1, 'numberOfElementPerPage' => $numberOfElementPerPage]);
        }

        /** Récupérer la liste des catégories, avec l'offset et la limit calculer avec PaginationService */
        $categoriesList = $categoryMdl->getCategoriesList(PaginationService::getOffsetForDB(), PaginationService::getLimitForDB());
        $this->render('Categories\CategoriesList', 'Liste des catégories', [
            'categoriesList'         => $categoriesList,
            'numberOfElementPerPage' => $numberOfElementPerPage,
        ]);
    }
}
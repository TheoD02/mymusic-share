<?php


namespace App\Controllers\FrontOffice\Categories;


use App\Models\Categories;
use App\Models\Tracks;
use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\PaginationService;

class CategoryController extends BaseController
{
    public function showCategory(string $slug, int $currentPage = 1): void
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


        $this->render('Category\Category', 'Catégorie : ' . $categoryName, [
            'viewTitle'    => $categoryName,
            'slug' => $slug,
            'tracksList'   => $tracksList ?? null,
        ]);
    }
}
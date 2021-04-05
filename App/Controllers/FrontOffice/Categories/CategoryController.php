<?php


namespace App\Controllers\FrontOffice\Categories;


use App\Models\Categories;
use App\Models\Tracks;
use App\Models\UsersDownloadLists;
use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\PaginationService;
use Core\UserHelper;

class CategoryController extends BaseController
{
    public function showCategory(string $slug, int $currentPage = 1): void
    {
        $trackModel = new Tracks();

        /** Récupérer la catégorie */
        $category = (new Categories())->setSlug($slug)
                                      ->getCategoryBySlug();

        /** Si on a bien une catégorie */
        if ($category !== false)
        {
            $categoryName = $category->getName();
            $trackModel->setIdCategories($category->getId());

            /** Défini les données de pagination (Nombre total éléments, Nombre d'éléments par page, Numéro de page courante) */
            PaginationService::setTotalNumberOfElements($trackModel->getTotalNumberOfTracksByCategory());
            PaginationService::setNumberOfElementsPerPage(5);
            PaginationService::setCurrentPage($currentPage);
            PaginationService::calculate();

            /** Récupérer la liste des artistes, avec l'offset et la limit calculer avec PaginationService */
            $tracksList = $trackModel->getTrackListByCategories(PaginationService::getOffsetForDB(), PaginationService::getLimitForDB());

            /** Récupérer les liste de téléchargement de l'utilisateur */
            if (UserHelper::isAuthAsAnyRole())
            {
                $userDownloadsListMdl = new UsersDownloadLists();
                $userDownloadsListMdl->setIdUsers(UserHelper::getUserID());
                $userDownloadsList = $userDownloadsListMdl->getUserDownloadsListByUserId();
            }
        }
        else
        {
            FlashMessageService::addErrorMessage('La catégorie que vous souhaité voir n\'existe pas.');
            $this->redirectWithAltoRouter('categoriesList');
        }


        $this->render('Category\Category', 'Catégorie : ' . $categoryName, [
            'viewTitle'         => $categoryName,
            'slug'              => $slug,
            'tracksList'        => $tracksList ?? null,
            'userDownloadsList' => $userDownloadsList ?? null,
        ]);
    }
}
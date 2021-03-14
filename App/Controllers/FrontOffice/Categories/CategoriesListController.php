<?php


namespace App\Controllers\FrontOffice\Categories;


use App\Models\Categories;
use Core\Base\BaseController;

class CategoriesListController extends BaseController
{
    public function showCategoriesList(): void
    {
        $categoriesList = (new Categories())->getCategoriesList();
        $this->render('Categories\CategoriesList', 'Liste des catégories', [
            'categoriesList' => $categoriesList,
        ]);
    }
}
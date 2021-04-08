<?php

namespace App\Controllers\BackOffice;

use App\Models\Categories;
use Core\Base\BaseAdminController;
use Core\Base\BaseView;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\PaginationService;

class AdminCategoriesManagementController extends BaseAdminController
{
    /**
     * Affiche la liste des catégories
     *
     * @param int $pageNumber
     *
     * @throws \Exception
     */
    public function showCategoriesList(int $pageNumber = 1): void
    {
        $categoryMdl = new Categories();

        /** Défini les données de pagination (Nombre total éléments, Nombre d'éléments par page, Numéro de page courante) */
        PaginationService::setTotalNumberOfElements($categoryMdl->getTotalNumberOfCategories());
        PaginationService::setNumberOfElementsPerPage(10);
        PaginationService::setCurrentPage($pageNumber);
        PaginationService::calculate();

        /** Récupérer la liste des catégories, avec l'offset et la limit calculer avec PaginationService */
        $categoriesList = $categoryMdl->getCategoriesList(PaginationService::getOffsetForDB(), PaginationService::getLimitForDB());
        $this->render('Categories/CategoriesList', 'Liste des catégories', ['categoriesList' => $categoriesList], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Vérifie le formulaire et ajoute la catégorie
     *
     * En cas d'erreurs re-affiche le formulaire avec les erreurs commises.
     *
     * @throws \Exception
     */
    public function addCategory(): void
    {
        /** Vérification du formulaire */
        $FV = new FormValidator($_POST, $_FILES);

        /** Vérifié que le formulaire est envoyé et que le token CSRF est valide */
        if ($FV->checkFormIsSend('addCategoryAction'))
        {
            /** Vérifier les champ si ils sont valide */
            $FV->verify('name')
               ->isNotEmpty();
            $FV->verify('slug')
               ->isNotEmpty()
               ->isAlphaNumeric(['-'], 'lower', true)
               ->minLength(1)
               ->maxLength(70);
            $FV->verifyFile('imgPath')
               ->fileMaxSize(12)
               ->fileIsFormat([FormValidator::IMAGE_JPG => 'jpg/jpeg', FormValidator::IMAGE_PNG => 'png']);

            /** Instanciation du model, on lui passe les données entrée par l'utilisateur */
            $categoriesModel = new Categories();
            $categoriesModel->setName($FV->getFieldValue('name'))
                            ->setSlug($FV->getFieldValue('slug'));

            /**
             * Vérification que le nom et le slug ne soit pas déjà utilisée
             */
            if ($categoriesModel->checkCategoryNameIsFree())
            {
                $FV->forceError('name', 'Le nom de la catégorie est déjà utilisée.');
            }
            if ($categoriesModel->checkCategorySlugIsFree())
            {
                $FV->forceError('slug', 'Le slug est déjà utilisée pour une autre catégorie.');
            }

            /** Les données du formulaire sont valide et le nom/slug est disponible donc on peu ajouter la catégorie */
            if ($FV->formIsValid())
            {
                /** Extension du fichier envoyée par l'utilisateur */
                // TODO : Récupérer le format du fichier via le mime_types serait plus prudent.
                $fileExtension         = pathinfo($_FILES['imgPath']['name'], PATHINFO_EXTENSION);
                $serverDestinationPath = CATEGORIES_IMG_PATH . $FV->getFieldValue('slug') . '.' . $fileExtension;
                $categoriesModel->setImgPath('/assets/img/categories/' . $FV->getFieldValue('slug') . '.' . $fileExtension);

                /** On essaye de déplacer le fichier, si une erreur intervient, on indique une erreur à l'utilisateur */
                if (!move_uploaded_file($_FILES['imgPath']['tmp_name'], $serverDestinationPath))
                {
                    FlashMessageService::addErrorMessage('Une erreur est survenue lors du déplacement de votre image sur notre serveur. Veuillez ressayer.');
                }
                else
                {
                    /** On ajoute la catégorie */
                    if ($categoriesModel->addCategory())
                    {
                        FlashMessageService::addSuccessMessage('Catégorie ajouté avec succès');
                        $this->redirect(\AltoRouter::getRouterInstance()
                                                   ->generate('adminCategoriesList'));
                    }
                    else
                    {
                        FlashMessageService::addSuccessMessage('Une erreur est survenue lors de l\'ajout de la catégorie, veuillez ressayer.');
                    }
                }
            }
        }
        else if (!$FV->checkFormIsSend('addCategory'))
        {
            $this->redirectWithAltoRouter('home');
        }
        $this->render('Categories/CategoryForm', 'Ajouter une catégorie', [
            'formTitle'       => 'Ajouter une catégorie',
            'formSubmitValue' => 'Confirmer l\'ajout de la catégorie',
            'formSubmitName'  => 'addCategoryAction',
        ], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Permet l'édition d'une catégorie
     *
     * @param int $id
     *
     * @throws \Exception
     */
    public function editCategory(int $id): void
    {
        $FV           = new FormValidator($_POST);
        $categoryInfo = (new Categories())->setId($id)->getCategoryById();

        /** Si le button d'édition à été cliquer et le token CSRF est valide */
        if ($FV->checkFormIsSend('editCategoryAction'))
        {
            $FV->verify('name')
               ->isNotEmpty();
            $FV->verify('slug')
               ->isNotEmpty()
               ->isAlphaNumeric(['-'], 'lower', true)
               ->minLength(1)
               ->maxLength(70);
            // TODO: Gérer le changement des image

            $categoriesModel = new Categories();
            $categoriesModel->setName($FV->getFieldValue('name'))
                            ->setSlug($FV->getFieldValue('slug'));

            /**
             * Vérification que le nom et le slug ne soit pas déjà utilisée par une autre catégorie
             */
            if ($categoriesModel->checkCategoryNameIsFree() && $categoryInfo->getName() !== $categoriesModel->getName())
            {
                $FV->forceError('name', 'Le nom de la catégorie est déjà utilisée.');
            }
            if ($categoriesModel->checkCategorySlugIsFree() && $categoryInfo->getSlug() !== $categoriesModel->getSlug())
            {
                $FV->forceError('slug', 'Le slug est déjà utilisée pour une autre catégorie.');
            }

            /** Les données du formulaire sont valide et le nom/slug est disponible donc on peu ajouter la catégorie */
            if ($FV->formIsValid())
            {
                $categoriesModel = new Categories();
                $categoriesModel->setId($id)->setName($FV->getFieldValue('name'))
                                ->setSlug($FV->getFieldValue('slug'));

                /** Mise à jour de la catégorie, en cas d'erreur le formulaire est ré-afficher avec une erreur */
                if ($categoriesModel->updateCategoryById())
                {
                    FlashMessageService::addSuccessMessage('Catégorie mis à jour avec succès !');
                    $this->redirect(\AltoRouter::getRouterInstance()
                                               ->generate('adminCategoriesList'));
                }
                FlashMessageService::addErrorMessage('Une erreur est survenue lors de la modification de la catégorie.');
            }
        }
        /** Si ce n'est pas l'action, ni le formulaire à afficher redirigé vers l'accueil */
        else if (!$FV->checkFormIsSend('editCategory'))
        {
            $this->redirectWithAltoRouter('home');
        }

        $this->render('Categories/CategoryForm', 'Edition de la catégorie - ' . $categoryInfo->getName(), [
            'formTitle'       => 'Edition de la catégorie',
            'formSubmitValue' => 'Enregistrer les modifications',
            'formSubmitName'  => 'editCategoryAction',
            'categoryInfo'    => $categoryInfo,
        ], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Supprime la catégorie
     *
     * @param int $id
     */
    public function deleteCategory(int $id): void
    {
        $FV = new FormValidator($_POST);
        if ($FV->checkFormIsSend('deleteCategory'))
        {
            $category = new Categories();
            $category->setId($id);
            if ($category->deleteCategoryById())
            {
                FlashMessageService::addSuccessMessage('Catégorie supprimée avec succès !');
            }
            else
            {
                FlashMessageService::addErrorMessage('Une erreur est survenue lors de la suppression de la catégorie, veuillez re-essayer.');
            }
        }
        $this->redirectWithAltoRouter('adminCategoriesList');
    }
}
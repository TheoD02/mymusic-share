<?php

namespace App\Controllers\BackOffice;

use App\Models\Artists;
use Core\Base\BaseAdminController;
use Core\Base\BaseView;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\PaginationService;

class AdminArtistsManagementController extends BaseAdminController
{
    /**
     * Affiche la liste des artistes
     *
     * @param int $pageNumber
     *
     * @throws \Exception
     */
    public function showArtistsList(int $pageNumber = 1): void
    {
        $artistMdl = new Artists();

        /** Défini les données de pagination (Nombre total éléments, Nombre d'éléments par page, Numéro de page courante) */
        PaginationService::setTotalNumberOfElements($artistMdl->getTotalNumberOfArtists());
        PaginationService::setNumberOfElementsPerPage(10);
        PaginationService::setCurrentPage($pageNumber);
        PaginationService::calculate();

        /** Récupérer la liste des artistes, avec l'offset et la limit calculer avec PaginationService */
        $artistList = $artistMdl->getArtistList(PaginationService::getOffsetForDB(), PaginationService::getLimitForDB());
        $this->render('Artists/ArtistsList', 'Liste des artistes', [
            'artistList' => $artistList,
        ], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Ajoute un artiste
     */
    public function addArtist(): void
    {
        $FV = new FormValidator($_POST);
        /** Vérifié que le formulaire est envoyé et que le token CSRF est valide */
        if ($FV->checkFormIsSend('addArtistAction'))
        {
            /** Vérifier le champ si il est valide */
            $FV->verify('name')->isNotEmpty()->minLength(3)->maxLength(100);

            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                $artistModel = new Artists();
                $artistModel->setName($FV->getFieldValue('name'));

                /** Vérifier si l'artiste que l'on souhaite ajouter n'existe pas déjà */
                if ($artistModel->searchOneArtistByName())
                {
                    $FV->forceError('name', 'Ce nom d\'artiste est déjà pris.');
                }
                /** Si le formulaire est toujours valide */
                if ($FV->formIsValid())
                {
                    /** Ajouter l'artiste */
                    if ($artistModel->addArtist())
                    {
                        FlashMessageService::addSuccessMessage('Artiste ajouté avec succès !');
                        $this->redirectWithAltoRouter('adminArtistsList');
                    }
                    else
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue pendant l\'ajout veuillez re-essayer.');
                    }
                }

            }
        }
        else if (!$FV->checkFormIsSend('addArtist'))
        {
            $this->redirectWithAltoRouter('home');
        }
        $this->render('Artists/ArtistForm', 'Ajouter un artiste', [
            'submitButtonValue' => 'Enregistrer l\'artiste',
            'submitButtonName'  => 'addArtistAction',
        ], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Affiche le formulaire d'édition d'un artiste avec les données en ligne
     *
     * @param int $id Id de l'artiste
     *
     * @throws \Exception
     */
    public function editArtist(int $id): void
    {
        $FV          = new FormValidator($_POST);
        $artistModel = new Artists();
        /** Récupère les information de l'artiste que l'on souhaite édité */
        $artistInfo = $artistModel->setId($id)->getArtistById();

        /** Vérifié que le formulaire est envoyé et que le token CSRF est valide */
        if ($FV->checkFormIsSend('editArtistAction'))
        {
            /** Vérifier le champ si il est valide */
            $FV->verify('name')->isNotEmpty()->minLength(3)->maxLength(100);

            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                $artistModel->setName($FV->getFieldValue());

                /** Mettre à jour l'artiste */
                if ($artistModel->updateArtistById())
                {
                    FlashMessageService::addSuccessMessage('Artiste éditer avec succès.');
                    $this->redirectWithAltoRouter('adminArtistsList');
                }
                else
                {
                    FlashMessageService::addErrorMessage('Une erreur est survenue lors de l\'enregistrement des modification de l\'artiste');
                }
            }
        }
        else if (!$FV->checkFormIsSend('editArtist'))
        {
            $this->redirectWithAltoRouter('home');
        }

        $this->render('Artists/ArtistForm', 'Edition de l\'artiste', [
            'submitButtonValue' => 'Enregistrer les modifications',
            'submitButtonName'  => 'editArtistAction',
            'artistInfo'        => $artistInfo,
        ], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Supprimer un artiste via son ID
     *
     * @param int $id
     */
    public function deleteArtist(int $id): void
    {
        $FV = new FormValidator($_POST);

        /** Vérifié que le formulaire est envoyé et que le token CSRF est valide */
        if ($FV->checkFormIsSend('deleteArtistAction'))
        {
            /** Supprimée l'artiste via son ID */
            if ((new Artists())->setId($id)->deleteArtistById())
            {
                FlashMessageService::addSuccessMessage('Artiste supprimé avec succès !');
            }
            else
            {
                FlashMessageService::addErrorMessage('Une erreur est survenue lors de la suppression de l\'artiste, veuillez re-essayer.');
            }
            $this->redirectWithAltoRouter('adminArtistsList');
        }
        $this->redirectWithAltoRouter('home');
    }
}
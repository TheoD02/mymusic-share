<?php

namespace App\Controllers\BackOffice;

use App\Models\Artists;
use Core\Base\BaseAdminController;
use Core\Base\BaseView;
use Core\FlashMessageService;
use Core\Form\FormValidator;

class AdminArtistsManagementController extends BaseAdminController
{
    /**
     * Affiche la liste des artistes
     *
     * @throws \Exception
     */
    public function showArtistsList(): void
    {
        $artistList = (new Artists())->getArtistList();
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
        if ($FV->checkFormIsSend('addArtistAction'))
        {
            $FV->verify('name')->isNotEmpty()->minLength(3)->maxLength(100);

            if ($FV->formIsValid())
            {
                $artistModel = new Artists();
                $artistModel->setName($FV->getFieldValue('name'));

                if ($artistModel->searchOneArtistByName())
                {
                    $FV->forceError('name', 'Ce nom d\'artiste est déjà pris.');
                }
                if ($FV->formIsValid())
                {
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
     */
    public function editArtist(int $id): void
    {
        $FV          = new FormValidator($_POST);
        $artistModel = new Artists();
        $artistInfo  = $artistModel->setId($id)->getArtistById();
        if ($FV->checkFormIsSend('editArtistAction'))
        {
            $FV->verify('name')->isNotEmpty()->minLength(3)->maxLength(100);

            if ($FV->formIsValid())
            {
                $artistModel->setName($FV->getFieldValue());

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

        $artistInfo = (new Artists())->setId($id)->getArtistById();
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
        if ($FV->checkFormIsSend('deleteArtistAction'))
        {
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
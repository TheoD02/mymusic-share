<?php


namespace App\Controllers\FrontOffice\UserProfile;

use App\Models\DownloadListTracks;
use App\Models\UsersDownloadLists;
use Core\Base\BaseController;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\UserHelper;

class UserDownloadListsController extends BaseController
{
    /** Vérifie que l'utilisateur soit connectée pour accèder à cet page */
    public function __construct()
    {
        parent::__construct();
        if (!UserHelper::isAuthAsAnyRole())
        {
            FlashMessageService::addErrorMessage('Vous devez être connecter pour accéder à cet page !');
            $this->redirectWithAltoRouter('login');
        }
    }

    /**
     * Affiche la liste de téléchargement de l'utilisateur
     *
     * @param int|null $id id de la liste de téléchargement à afficher
     *
     * @throws \Exception
     */
    public function showDownloadList(int $id = null): void
    {
        $userDownloadsListMdl = new UsersDownloadLists();
        $userDownloadsListMdl->setIdUsers(UserHelper::getUserID());

        /** Récupérer les liste de téléchargement de l'utilisateur */
        $downloadsList = $userDownloadsListMdl->getUserDownloadsListByUserId();

        /** Si des liste de téléchargement sont existante */
        if ($downloadsList !== false)
        {
            /** Si aucun ID de liste de téléchargement n'a été envoyé en paramètre */
            if ($id === null)
            {
                /** On récupère la première liste de téléchargement de l'utilisateur ou false si il n'a pas de liste encore créer */
                $userDownloadList = $downloadsList[0] ?? false;

                /** Si l'utilisateur à une liste de lecture  */
                if ($userDownloadList)
                {
                    $id                   = $userDownloadList->getId();
                    $downloadListTrackMdl = (new DownloadListTracks())->setIdUsersDownloadLists($id);
                    $tracksList           = $downloadListTrackMdl->getTracks();
                }
                else
                {
                    $tracksList = null;
                }
            }
        }

        $this->render('UserProfile/ProfileDownloadLists', 'Liste de téléchargement', [
            'downloadsList'  => $downloadsList,
            'tracksList'     => $tracksList ?? null,
            'downloadListId' => $id,
        ]);
    }

    public function deleteDownloadList(): void
    {
        $FV = new FormValidator($_POST);
        /** Vérifier que le formulaire soit envoyée et que le token CSRF soit valide */
        if ($FV->checkFormIsSend('removeDownloadListAction'))
        {
            /** Vérifier que l'id est bien de type int */
            $FV->verify('downloadsListId')->isValidSelect()->isInt();

            /** Si le formulaire est valide */
            if ($FV->formIsValid())
            {
                $downloadListMdl = new UsersDownloadLists();
                $downloadListMdl->setId($FV->getFieldValue('downloadsListId'));

                /** Supprimer la liste de téléchargement */
                if ($downloadListMdl->deleteDownloadListById())
                {
                    unset($_SESSION['user']['selectedDownloadListId']);
                    FlashMessageService::addSuccessMessage('Liste de téléchargement supprimée avec succès !');
                }
                else
                {
                    FlashMessageService::addSuccessMessage('Une erreur est survenue lors de suppression de la liste de téléchargement, veuillez ré-essayer.');
                }
                $this->redirectWithAltoRouter('profileDownloadLists');
            }
        }
    }
}
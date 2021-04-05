<?php


namespace App\Controllers\FrontOffice;


use App\Models\Tracks;
use App\Models\UsersDownloadLists;
use Core\Base\BaseController;
use Core\UserHelper;

class NewReleaseController extends BaseController
{
    public function showNewRelease(): void
    {
        /** Récupérer les liste de téléchargement de l'utilisateur */
        if (UserHelper::isAuthAsAnyRole())
        {
            $userDownloadsListMdl = new UsersDownloadLists();
            $userDownloadsListMdl->setIdUsers(UserHelper::getUserID());
            $userDownloadsList = $userDownloadsListMdl->getUserDownloadsListByUserId();
        }

        /** Récupère la liste des dernières musiques sorties */
        $tracksList = (new Tracks())->getTracksList(false, 0, 50);
        $this->render('NewRelease/NewRelease', 'Nouveautés', [
            'tracksList'        => $tracksList,
            'userDownloadsList' => $userDownloadsList ?? null,
        ]);
    }
}
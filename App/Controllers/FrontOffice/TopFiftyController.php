<?php


namespace App\Controllers\FrontOffice;


use App\Models\Tracks;
use App\Models\UsersDownloadLists;
use Core\Base\BaseController;
use Core\UserHelper;

class TopFiftyController extends BaseController
{
    public function showTopFiftyDownloaded(): void
    {
        /** Récupérer les liste de téléchargement de l'utilisateur */
        if (UserHelper::isAuthAsAnyRole())
        {
            $userDownloadsListMdl = new UsersDownloadLists();
            $userDownloadsListMdl->setIdUsers(UserHelper::getUserID());
            $userDownloadsList = $userDownloadsListMdl->getUserDownloadsListByUserId();
        }


        /** Récupérer la liste des musique les plus télécharger */
        $tracksList = (new Tracks())->getTopDownloadedTracks(50);
        $this->render('TopFifty', 'Top 50 : Les plus téléchargées', [
            'viewTitle'         => 'Top 50 : Les plus téléchargées',
            'userDownloadsList' => $userDownloadsList ?? null,
            'tracksList'        => $tracksList ?? null,
        ]);
    }

    public function showTopFiftyListened(): void
    {
        /** Récupérer les liste de téléchargement de l'utilisateur */
        if (UserHelper::isAuthAsAnyRole())
        {
            $userDownloadsListMdl = new UsersDownloadLists();
            $userDownloadsListMdl->setIdUsers(UserHelper::getUserID());
            $userDownloadsList = $userDownloadsListMdl->getUserDownloadsListByUserId();
        }

        /** Récupérer la liste des musique les plus écouter */
        $tracksList = (new Tracks())->getTopListenedTracks(50);
        $this->render('TopFifty', 'Top 50 : Les plus écouter', [
            'viewTitle'         => 'Top 50 : Les plus écouter',
            'userDownloadsList' => $userDownloadsList ?? null,
            'tracksList'        => $tracksList ?? null,
        ]);
    }
}
<?php


namespace App\Controllers;


use Apfelbox\FileDownload\FileDownload;
use App\Models\DownloadListTracks;
use App\Models\Tracks;
use App\Models\Users;
use App\Models\UsersDownloadedTracks;
use App\Models\UsersDownloadLists;
use Core\Base\BaseController;
use Core\Form\FormValidator;
use Core\UserHelper;
use ZipArchive;

class DownloadController extends BaseController
{
    /**
     * Gère le téléchargement de la musique
     *
     * @param string $hash
     */
    public function downloadTrack(string $hash): void
    {
        /** L'utilisateur est connectée */
        if (UserHelper::isAuthAsAnyRole())
        {
            $user = new Users();
            $user->setId(UserHelper::getUserID());
            /** Récupérer le nombre de téléchargement restant possible pour cet utilisateur */
            $remainingDownloadCount = $user->getRemainingDownloadById();
            /** L'utilisateur à le droit de télécharger */
            if ($remainingDownloadCount !== null && $remainingDownloadCount > 0)
            {
                /** Récupérer la musique via son hash */
                $track = (new Tracks())->setHash($hash)->getMp3ByHash();

                /** Une musique à était trouver */
                if ($track !== false)
                {
                    $userDownloadedTrack = new UsersDownloadedTracks();
                    $userDownloadedTrack->setIdUsers(UserHelper::getUserID())->setIdTracks($track->getId());

                    /** L'utilisateur à déjà télécharger la musique ? (true|false) */
                    $userAlreadyDownloadedTrack = $userDownloadedTrack->userAlreadyDownloadTrackById();

                    /** Si l'utilisateur n'a pas encore télécharger cet musique décrémenter son compteur et ajoute la musique comme télécharger */
                    if (!$userAlreadyDownloadedTrack)
                    {
                        $user->decrementRemainingDownload();
                        $userDownloadedTrack->addTrackToDownloaded();
                    }

                    $trackFullPath = APP_ROOT . 'public/' . $track->getPath();
                    $trackFileName = $track->getArtistsName() . ' - ' . $track->getTitle() . '.' . pathinfo($track->getPath(), PATHINFO_EXTENSION);

                    $fileDownload = FileDownload::createFromFilePath($trackFullPath);
                    $fileDownload->sendDownload($trackFileName);
                }
            }
            else
            {
                UserHelper::setIsAuthorizedToDownload(false);
                header('HTTP/1.1 403 Forbidden');
                (new ErrorController())->forbidden();
                exit();
            }
        }
        else
        {
            header('HTTP/1.1 401 Unauthorized', 401);
            (new ErrorController())->forbidden();
            exit();
        }
    }

    /** Créer un fichier temp_music_zip temporaire pour que l'utilisateur puissent télécharger sa liste de téléchargement */
    public function createAndDownloadZip(): void
    {
        $FV = new FormValidator($_POST);
        if ($FV->checkFormIsSend('downloadListAction'))
        {
            $FV->verify('downloadListId')->isInt();
            if ($FV->formIsValid())
            {
                $trackInList      = (new DownloadListTracks())->setIdUsersDownloadLists($FV->getFieldValue('downloadListId'))->getTracks();
                $downloadListInfo = (new UsersDownloadLists())->setId($FV->getFieldValue('downloadListId'))->setIdUsers(UserHelper::getUserID())
                                                              ->getUserDownloadListByIdAndUserId();
                if ($trackInList !== false && $downloadListInfo !== false)
                {
                    ignore_user_abort(true);
                    /** Ne pas utiliser la session pour ce script, il bloque le chargement de tout autre script charger tant que ce script est en cours */
                    session_write_close();

                    $zipFileName = $downloadListInfo->getName() . '.zip';
                    $zipFullPath = APP_ROOT . 'temp_music_zip/' . $zipFileName;
                    $zipManager  = new ZipArchive();
                    $zipManager->open($zipFullPath, ZipArchive::CREATE);

                    foreach ($trackInList as $trackInfo)
                    {
                        $zipManager->addFile(MP3_FINAL_PATH . $trackInfo->getHash() . '.mp3', $trackInfo->getArtistsName() . ' - ' . $trackInfo->getTitle() . '.mp3');
                    }

                    $zipManager->close();

                    $fileDownload = FileDownload::createFromFilePath($zipFullPath);
                    $fileDownload->sendDownload($zipFileName);
                    unlink($zipFullPath);
                }
                $this->redirectWithAltoRouter('profileDownloadLists');
            }
        }
    }
}
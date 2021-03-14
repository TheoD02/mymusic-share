<?php


namespace App\Controllers;


use Apfelbox\FileDownload\FileDownload;
use App\Models\Tracks;
use App\Models\Users;
use App\Models\UsersDownloadedTracks;
use Core\UserHelper;

class DownloadController
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
            $remainingDownload = $user->getRemainingDownloadById();
            /** L'utilisateur à le droit de télécharger */
            if ($remainingDownload !== null && $remainingDownload > 0)
            {
                $track = (new Tracks())->setHash($hash)->getMp3ByHash();

                $userDownloadedTrack = new UsersDownloadedTracks();
                $userDownloadedTrack->setIdUsers(UserHelper::getUserID())->setIdTracks($track->getId());

                $userAlreadyDownloadedTrack = $userDownloadedTrack->userAlreadyDownloadTrackById();
                if ($track !== false)
                {

                    $trackFullPath = APP_ROOT . 'public/' . $track->getPath();
                    $trackFileName = $track->getArtistsName() . ' - ' . $track->getTitle() . '.' . pathinfo($track->getPath(), PATHINFO_EXTENSION);
                    $fileDownload  = FileDownload::createFromFilePath($trackFullPath);
                    $fileDownload->sendDownload($trackFileName);
                    if (!$userAlreadyDownloadedTrack)
                    {
                        $user->decrementRemainingDownload();
                        $userDownloadedTrack->addTrackToDownloaded();
                    }
                }
            }
            else
            {
                header('HTTP/1.1 403 Forbidden');
                exit();
            }
        }
        else
        {
            header('HTTP/1.1 423 Locked', 423);
            exit();
        }
    }
}
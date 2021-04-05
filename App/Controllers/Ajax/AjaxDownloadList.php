<?php


namespace App\Controllers\Ajax;


use App\Models\DownloadListTracks;
use App\Models\Tracks;
use App\Models\UsersDownloadLists;
use Core\UserHelper;

class AjaxDownloadList
{
    public function addDownloadList(): void
    {
        /** Si l'utilisateur n'est pas connecté */
        if (!UserHelper::isAuthAsAnyRole())
        {
            echo json_encode(['message' => 'Utilisateur non connectée.'], JSON_THROW_ON_ERROR);
            return;
        }

        $downloadListMdl = new UsersDownloadLists();
        /** Si on a bien reçu un nom de catégorie */
        if (!empty($_POST['categoryName']))
        {
            /** Défini qu'elle utilisateur va ajouter une liste et le nom de la liste */
            $downloadListMdl->setIdUsers(UserHelper::getUserID())->setName($_POST['categoryName']);

            /** Vérifier que le nom de la liste de téléchargement n'a pas déjà était créer par cet utilisateur */
            if (!$downloadListMdl->checkDownloadListNameIsFreeByNameAndUserID())
            {
                /** Ajouter la liste */
                if ($downloadListMdl->addDownloadList())
                {
                    echo json_encode(['message' => 'Catégorie ajouté avec succès', 'downloadListID' => $downloadListMdl->getLastInsertId()], JSON_THROW_ON_ERROR);
                }
                else
                {
                    echo json_encode(['message' => 'Une erreur est survenue lors de l\'ajout de la catégorie'], JSON_THROW_ON_ERROR);
                }
            }
            else
            {
                echo json_encode(['message' => 'Impossible d\'ajouter une catégorie avec ce nom car elle existe déjà'], JSON_THROW_ON_ERROR);
            }
        }
        else
        {
            echo json_encode(['message' => 'Une erreur est survenue.'], JSON_THROW_ON_ERROR);
        }
    }

    /**
     * Récupère les information de la liste de téléchargement de l'utilisateur
     *
     * @param int|null $id id de la liste de téléchargement
     */
    public function getDownloadListById(int $id = null): void
    {
        /** L'utilisateur doit être connecté si non la connexion est refusé */
        if (!UserHelper::isAuthAsAnyRole())
        {
            echo 'Unauthorized';
            header('401 Unauthorized', 406);
            exit;
        }

        /** Si on a un ID */
        if ($id !== null)
        {
            /** On met a jour l'id de la liste sélectionner en session */
            $this->setDownloadListIdInSession($id);
            /** Instanciation du model on défini l'id l'utilisateur qui fait la demande puis l'id de la liste a rechercher */
            $userDownloadListMdl = new UsersDownloadLists();
            $userDownloadListMdl->setIdUsers(UserHelper::getUserID())->setId($id);
            /** Retourne les info de la liste de téléchargement ou false */
            $downloadListInfo = $userDownloadListMdl->getUserDownloadListByIdAndUserId();

            /** On a trouver une liste de téléchargement liée à l'utilisateur */
            if ($downloadListInfo !== false)
            {
                /** Récupérer les musique lier à cet liste de téléchargement */
                $downloadListTracksMdl = new DownloadListTracks();
                $downloadListTracksMdl->setIdUsersDownloadLists($downloadListInfo->getId());
                $tracksList = $downloadListTracksMdl->getTracks();

                /** Générer le tableau de musique puis l'afficher */
                ob_start();
                require APP_ROOT . 'App/Views/FrontOffice/MusicTable.php';
                $tableContent = ob_get_clean();
                echo $tableContent;
                return;
            }
        }
        echo 'Not Found';
        header('404 Not Found', 404);
        exit;
    }

    /** Définir l'id de la liste de téléchargement sélectionner en session
     *
     * @param int $id id de la liste de téléchargement
     */
    public function setDownloadListIdInSession(int $id): void
    {
        $_SESSION['user']['selectedDownloadListId'] = $id;
    }

    /**
     * Ajouter une musique a une liste de téléchargement
     *
     * @param int    $playlistId ID de la liste de téléchargement
     * @param string $trackHash  Hash de la musique a ajouter
     *
     * @throws \JsonException
     */
    public function addTrackToDownloadList(int $playlistId, string $trackHash): void
    {
        /** L'utilisateur doit être connecter */
        if (UserHelper::isAuthAsAnyRole())
        {
            /** Récupérer l'id de la musique */
            $trackInfo = (new Tracks())->setHash($trackHash)->getTrackIdByHash();
            /** Si on a réussi a trouver une musique avec le hash */
            if ($trackInfo !== false)
            {
                /** On instancie notre model et on lui donne l'id de la liste de téléchargement et l'id de la musique a ajouter */
                $downloadListTracksMdl = new DownloadListTracks();
                $downloadListTracksMdl->setIdUsersDownloadLists($playlistId)->setIdTracks($trackInfo->getId());

                /** Vérifier que la musique n'est pas déjà dans la liste de téléchargement */
                if ($downloadListTracksMdl->checkTrackIsAlreadyInDownloadList())
                {
                    echo json_encode(['message' => 'Cet musique est déjà dans la liste de téléchargement.', 'isAdded' => 'warning'], JSON_THROW_ON_ERROR);
                    return;
                }
                /** Vérifier que la liste de téléchargement ne dépasse pas la limite totale */
                else if ($downloadListTracksMdl->countNumberOfTrackInDownloadList() >= 40)
                {
                    echo json_encode(['message' => 'Vous avez atteint la limite de musique possible de 40 sur cet liste de téléchargement.', 'isAdded' => 'error'], JSON_THROW_ON_ERROR);
                    return;
                }
                /** Si tout est OK on ajoute la musique */
                else if ($downloadListTracksMdl->addTrackToDownloadListByIdTrackAndIdCategory())
                {
                    echo json_encode(['message' => 'Musique ajouté à votre liste de téléchargement.', 'isAdded' => 'success'], JSON_THROW_ON_ERROR);
                    return;
                }
            }
        }
        echo json_encode(['message' => 'Une erreur est survenue lors de l\'ajout de votre musique dans la liste de téléchargement.', 'isAdded' => 'error'], JSON_THROW_ON_ERROR);
    }
}
<?php

namespace App\Controllers\BackOffice;

use AltoRouter;
use App\Models\Artists;
use App\Models\ArtistsTracks;
use App\Models\Categories;
use App\Models\MusicKey;
use App\Models\Tracks;
use Core\Base\BaseAdminController;
use Core\Base\BaseView;
use Core\Database;
use Core\FlashMessageService;
use Core\Form\FormValidator;
use Core\Security;
use Core\SpaceDiskHelper;
use Exception;

class AdminMusicsManagementController extends BaseAdminController
{
    /**
     * Affiche la liste des musique en ligne et en attente
     *
     * @throws \Exception
     */
    public function showMusicList(): void
    {
        $tracksList = (new Tracks())->getTracksList(true, 0, 100);
        $this->render('Musics/MusicsList', 'Liste des musique en ligne', ['tracksList' => $tracksList], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Affiche la liste des musique en attente de mise en ligne
     *
     * @throws \Exception
     */
    public function showPendingMusicsList(): void
    {
        $tracksList = (new Tracks())->getPendingTracksList();
        $this->render('Musics/MusicsList', 'Liste des musique en ligne', ['tracksList' => $tracksList], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Gère le formulaire d'ajout de musique
     *
     * @throws \Exception
     */
    public function addMusic(): void
    {
        SpaceDiskHelper::checkFreeSpace(2);
        $FV = new FormValidator($_POST, $_FILES);
        /** Le formulaire existe et le token CSRF est valide. */
        if ($FV->checkFormIsSend('uploadMusicAction'))
        {
            /**
             * Vérifie si c'est le champ "musicFile" qui est reçu
             *
             * ou le champ "tempMusicFile" avec le nom du fichier temporaire
             * (e.g Dans le cas ou le formulaire à déjà été envoyé mais l'utilisateur à fait des erreurs)
             */
            if ($FV->checkFileFieldExist('musicFile'))
            {
                $FV->verifyFile('musicFile')
                   ->fileIsFormat([FormValidator::AUDIO_MPEG => 'mp3'])
                   ->fileMaxSize(25);
                /** Chemin d'accès vers le fichier temporaire */
                $tempFileName = $this->moveUploadedFileToTempDir();
            }
            else
            {
                $FV->verify('tempMusicFile')
                   ->isContain('MP3')
                   ->isAlphaNumeric([], 'upper', true);
                // On récupère le nom du fichier temporaire et création du chemin d'accès
                $tempFileName = $FV->getFieldValue('tempMusicFile');
            }
            $FV->verify('title')
               ->isNotEmpty()
               ->minLength(1)
               ->maxLength(255);
            $FV->verify('artists')
               ->isNotEmpty()
               ->minLength(1)
               ->maxLength(100);
            $FV->verify('bpm')
               ->isInt(50, 300)
               ->setCustomInvalidFeedback('Veuillez saisir un BPM entre 50 et 300.');
            $FV->verify('bitrate')
               ->isValidSelect()
               ->isInt(128, 320)
               ->setCustomInvalidFeedback('Veuillez sélectionner un bitrate.');
            $FV->verify('id_musicKey')
               ->isValidSelect()
               ->isInt()
               ->minLength(1)
               ->maxLength(2)
               ->setCustomInvalidFeedback('Veuillez sélectionner une clé harmonique.');
            $FV->verify('id_categories')
               ->isValidSelect()
               ->setCustomInvalidFeedback('Veuillez sélectionner une catégorie.');
            $FV->verify('isPending')->isValidSelect()->isInt(0, 1)->setCustomInvalidFeedback('Veuillez sélectionner un statut');

            /** Le formulaire est valide */
            if ($FV->formIsValid())
            {
                try
                {
                    /** Démarrer une transaction */
                    Database::getPDOInstance()->beginTransaction();

                    $trackModel = new Tracks();
                    $trackModel->setTitle($FV->getFieldValue('title'))
                               ->setBpm($FV->getFieldValue('bpm'))
                               ->setBitrate($FV->getFieldValue('bitrate'))
                               ->setIsPending((bool)$FV->getFieldValue('isPending'))
                               ->setIdMusicKey($FV->getFieldValue('id_musicKey'))
                               ->setIdCategories($FV->getFieldValue('id_categories'))
                               ->setHash(Security::generateToken(15) . (new \DateTime())->getTimestamp() . Security::generateToken(15));

                    /** Récupérer les ID des artistes trouver ou ajouter */
                    $listOfArtistsId = $this->addOrGetArtistId($FV->getFieldValue('artists'));

                    /** Si la musique est envoyé dans les musique en attente la placer dans le dossier awaiting_tracks */
                    if ($trackModel->isPending())
                    {
                        /** Chemin d'accès au fichier temporaire */
                        $tempPath = MP3_TEMP_PATH . $tempFileName . '.tmp';
                        /** Chemin absolue vers le chemin de destination (sert à déplacer le fichier) */
                        $finalDestinationPath = MP3_AWAITING_PATH . $trackModel->getHash() . '.mp3';
                        /** Chemin relatif pour l'affichage dans les page web (stocker en base de données) */
                        $finalRelativeFilePath = '/assets/musics/awaiting_tracks/' . $trackModel->getHash() . '.mp3';
                    }
                    else
                    {
                        /** Chemin d'accès au fichier temporaire */
                        $tempPath = MP3_TEMP_PATH . $tempFileName . '.tmp';
                        /** Chemin absolue vers le chemin de destination (sert à déplacer le fichier) */
                        $finalDestinationPath = MP3_FINAL_PATH . $trackModel->getHash() . '.mp3';
                        /** Chemin relatif pour l'affichage dans les page web (stocker en base de données) */
                        $finalRelativeFilePath = '/assets/musics/categories/' . $trackModel->getHash() . '.mp3';
                    }
                    $trackModel->setPath($finalRelativeFilePath);

                    /** Déplacer le fichier à la destination final */
                    if (!rename($tempPath, $finalDestinationPath))
                    {
                        FlashMessageService::addErrorMessage('Une erreur est survenue lors du déplacement de votre fichier sur notre serveur. Veuillez ressayer si le problème persiste veuillez contacter un administrateur du site.');
                    }
                    else
                    {
                        /** Ajouter la musique en base de données */
                        if ($trackModel->addMusic())
                        {
                            /** Associer le/les artiste à la musique via son ID */
                            $this->associateArtistToTrack($trackModel->getLastInsertId(), $listOfArtistsId);
                            Database::getPDOInstance()->commit();

                            FlashMessageService::addSuccessMessage('La musique [' . $trackModel->getTitle() . '] à bien été mis en ligne.');
                            $this->redirect(AltoRouter::getRouterInstance()
                                                      ->generate('adminMusicsList'));

                        }
                        else
                        {
                            FlashMessageService::addErrorMessage('Une erreur est survenue lors de la mise en ligne de votre musique. Veuillez ressayer.');
                        }
                    }
                }
                catch (Exception $e)
                {
                    Database::getPDOInstance()->rollBack();
                    die($e->getMessage());
                }
            }
        }
        else if (!$FV->checkFormIsSend('uploadMusic'))
        {
            $this->redirectWithAltoRouter('home');
        }

        /** Récupère les clé harmonique et la liste des catégories */
        $musicKeyList   = (new MusicKey())->getMusicKeyList();
        $categoriesList = (new Categories())->getCategoriesList();
        $this->render('Musics/UploadMusicForm', 'Ajouter une musique', [
            'formButtonName'  => 'uploadMusicAction',
            'formButtonValue' => 'Confirmer l\'ajout du fichier audio',
            'musicKeyList'    => $musicKeyList,
            'categoriesList'  => $categoriesList,
            'tempFileName'    => $tempFileName ?? null,
        ], BaseView::BACK_OFFICE_PATH);
    }

    public function editMusic(int $id): void
    {
        $FV        = new FormValidator($_POST);
        $musicInfo = (new Tracks())->setId($id)->getTrackById();
        /** Le formulaire existe et le token CSRF est valide. */
        if ($FV->checkFormIsSend('editMusicAction'))
        {
            $FV->verify('title')
               ->isNotEmpty()
               ->minLength(1)
               ->maxLength(255);
            $FV->verify('artists')
               ->isNotEmpty()
               ->minLength(1)
               ->maxLength(100);
            $FV->verify('bpm')
               ->isInt(50, 300)
               ->setCustomInvalidFeedback('Veuillez saisir un BPM entre 50 et 300.');
            $FV->verify('bitrate')
               ->isValidSelect()
               ->isInt(128, 320)
               ->setCustomInvalidFeedback('Veuillez sélectionner un bitrate.');
            $FV->verify('id_musicKey')
               ->isValidSelect()
               ->isInt()
               ->minLength(1)
               ->maxLength(2)
               ->setCustomInvalidFeedback('Veuillez sélectionner une clé harmonique.');
            $FV->verify('id_categories')
               ->isValidSelect()
               ->setCustomInvalidFeedback('Veuillez sélectionner une catégorie.');
            $FV->verify('isPending')->isValidSelect()->isInt(0, 1)->setCustomInvalidFeedback('Veuillez sélectionner un statut');

            /** Le formulaire est valide */
            if ($FV->formIsValid())
            {
                try
                {
                    /** Démarrer une transaction */
                    Database::getPDOInstance()->beginTransaction();

                    $trackModel = new Tracks();

                    $trackModel->setId($id)->setTitle($FV->getFieldValue('title'))
                               ->setBpm($FV->getFieldValue('bpm'))
                               ->setBitrate($FV->getFieldValue('bitrate'))
                               ->setPath($musicInfo->getPath())
                               ->setIsPending((bool)$FV->getFieldValue('isPending'))
                               ->setIdMusicKey($FV->getFieldValue('id_musicKey'))
                               ->setIdCategories($FV->getFieldValue('id_categories'));

                    /** Récupère tous les id des artistes associer à la musique */
                    $currentArtistsId = array_column($trackModel->getArtistsIds(), 'id_artists');

                    /** Récupérer les ID des artistes existant ou ajouter */
                    $listOfArtistsId = $this->addOrGetArtistId($FV->getFieldValue('artists'));

                    /** Contient les artiste à ajouter */
                    $artistToAdd = array_diff($listOfArtistsId, $currentArtistsId);
                    /** Contient les artistes à supprimer */
                    $artistToDissociate = array_diff($currentArtistsId, $listOfArtistsId);

                    /** Si il y des artiste à associer */
                    if (!empty($artistToAdd))
                    {
                        /** Associer le/les artiste à la musique via son ID */
                        $this->associateArtistToTrack($trackModel->getId(), $artistToAdd);
                    }
                    /** Si il y a des artiste à dissocier */
                    if (!empty($artistToDissociate))
                    {
                        /** Dissocie le/les artiste à la musique via son ID */
                        $this->dissociateArtistFromTrack($trackModel->getId(), $artistToDissociate);
                    }

                    /** Si la musique est dans le dossier d'attente et que le statut est passer en ligne */
                    if (strpos($musicInfo->getPath(), 'awaiting_tracks') !== false && $musicInfo->isPending() === true)
                    {
                        /** Nom du fichier MP3 xxx.mp3 */
                        $mp3FileName = pathinfo($musicInfo->getPath(), PATHINFO_BASENAME);

                        /** Chemin d'accès absolue actuel de la musique */
                        $currentAbsolutePath = APP_ROOT . 'public' . $musicInfo->getPath();
                        $currentAbsolutePath = str_replace('//', '/', $currentAbsolutePath);

                        /** Nouveau chemin relatif de la musique */
                        $destinationRelativePath = '/assets/musics/categories/' . $mp3FileName;
                        /** Nouveau chemin absolue de la musique */
                        $destinationAbsolutePath = APP_ROOT . 'public' . $destinationRelativePath;
                        /** Déplacer le fichier existant dans le dossier categories */
                        if (rename($currentAbsolutePath, $destinationAbsolutePath))
                        {
                            $trackModel->setPath($destinationRelativePath);
                        }
                    }
                    /** Mettre à jour les informations de la musique */
                    if ($trackModel->updateTrackInfoById())
                    {
                        Database::getPDOInstance()->commit();
                        FlashMessageService::addSuccessMessage('Musique mise à jour avec succès.');
                        $this->redirectWithAltoRouter('adminMusicsList');
                    }
                }
                catch (Exception $e)
                {
                    Database::getPDOInstance()->rollBack();
                    die($e->getMessage());
                }
            }
        }
        else if (!$FV->checkFormIsSend('editMusic'))
        {
            $this->redirectWithAltoRouter('home');
        }

        /** Récupère les clé harmonique et la liste des catégories */
        $musicKeyList   = (new MusicKey())->getMusicKeyList();
        $categoriesList = (new Categories())->getCategoriesList();
        $this->render('Musics/UploadMusicForm', 'Edition de musique', [
            'formButtonName'  => 'editMusicAction',
            'formButtonValue' => 'Enregistrer les modifications',
            'musicKeyList'    => $musicKeyList,
            'categoriesList'  => $categoriesList,
            'musicInfo'       => $musicInfo,
        ], BaseView::BACK_OFFICE_PATH);
    }

    /**
     * Supprime une musique
     *
     * @param int $id
     */
    public function deleteMusicAction(int $id): void
    {
        $FV = new FormValidator($_POST);
        if ($FV->checkFormIsSend('deleteMusicAction'))
        {
            $trackModel = new Tracks();
            $trackModel->setId($id);
            if ($trackModel->deleteTrackById())
            {
                FlashMessageService::addSuccessMessage('Musique supprimée avec succès.');
            }
            else
            {
                FlashMessageService::addErrorMessage('Une erreur est survenue lors de la suppression de la musique.');
            }
            $this->redirectWithAltoRouter('adminMusicsList');
        }
        else
        {
            $this->redirectWithAltoRouter('home');
        }
    }

    /**
     * Créer un fichier temporaire, copie le contenu du fichier uploader dans le fichier temporaire
     * puis renvoi le nom du fichier temporaire
     *
     * @return string
     */
    private function moveUploadedFileToTempDir(): string
    {
        // Créer le fichier temporaire
        $tempFileDestination = tempnam(MP3_TEMP_PATH, 'MP3');
        // Ouvrir et lire les données du fichier audio envoyée par l'utilisateur
        $comingFile = fopen($_FILES['musicFile']['tmp_name'], 'rb');
        $fileData   = fread($comingFile, $_FILES['musicFile']['size']);
        // On ouvre le fichier temporaire
        $tempFile = fopen($tempFileDestination, 'wb');
        // On écrit les données du fichier reçu dans le fichier temporaire
        fwrite($tempFile, $fileData);
        // Fermeture des 2 fichiers.
        fclose($comingFile);
        fclose($tempFile);

        // Récupération du nom du fichier temporaire
        $tempFileName = pathinfo($tempFileDestination, PATHINFO_FILENAME);
        return $tempFileName;
    }

    /**
     * Vérifie si un artiste existe via son nom, Si il existe il récupère l'ID sinon il ajoute
     * l'artiste est récupère le dernier ID insérer
     *
     * @param string $listOfArtistsName Liste de noms des artistes
     *
     * @return array
     */
    public function addOrGetArtistId(string $listOfArtistsName): array
    {
        /** Ajouter le/les artists si il n'existe pas et récupérer l'ID si il a été créer ou déjà existant */
        /** Liste des artistes */
        $listOfArtistsName = explode(',', $listOfArtistsName);
        /** Liste des ID's des artistes */
        $listOfArtistsId = [];
        /** Instanciation du model */
        $artistsModel = new Artists();

        foreach ($listOfArtistsName as $artistName)
        {
            $artistsModel->setName($artistName);
            /** On recherche un artiste avec son nom */
            $artist = $artistsModel->searchOneArtistByName();
            /** Si il n'existe pas on va créer l'artiste */
            if ($artist === false)
            {
                /** Création de l'artiste et récupération de son ID */
                $artistsModel->addArtist();
                $listOfArtistsId[] = $artistsModel->getLastInsertId();
            }
            /** Sinon l'artiste existe on peu récupérer son ID associé */
            else
            {
                $listOfArtistsId[] = $artist->getId();
            }
        }
        return $listOfArtistsId;
    }

    /**
     * Associe le ou les artistes à une musique
     *
     * @param int   $trackId         ID de la musique
     * @param array $listOfArtistsId Tableau contenant les ID's des artistes à ajouter
     */
    public function associateArtistToTrack(int $trackId, array $listOfArtistsId): void
    {
        $artistsTracksModel = new ArtistsTracks();
        $artistsTracksModel->setIdTracks($trackId);

        /** Associe chaque artiste */
        foreach ($listOfArtistsId as $idArtist)
        {
            $artistsTracksModel->setIdArtists($idArtist);
            /** Associe l'artiste à la musique via leurs ID */
            $artistsTracksModel->associateArtistToTrack();
        }
    }

    /**
     * Dissocie les artistes qui sont associé à une musique
     *
     * @param int   $trackId         ID de la musique
     * @param array $listOfArtistsId Tableau contenant les ID's des artistes à supprimer
     */
    public
    function dissociateArtistFromTrack(int $trackId, array $listOfArtistsId): void
    {
        $artistsTracksModel = new ArtistsTracks();
        $artistsTracksModel->setIdTracks($trackId);
        // Dissocié chaque artiste de la musique
        foreach ($listOfArtistsId as $idArtist)
        {
            $artistsTracksModel->setIdArtists($idArtist);
            /** Dissocie l'artiste de la musique via leurs ID */
            $artistsTracksModel->dissociateArtistFromTrackById();
        }
    }
}
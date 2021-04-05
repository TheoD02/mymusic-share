<?php

use App\Models\Categories;
use App\Models\Tracks;
use Core\UserHelper;

$router = AltoRouter::getRouterInstance();
/** @var Categories|false $categoryInfo */
/** @var Tracks[]|false $tracksList */
?>
<div class="col-md-12 mt-5 px-4">
    <?php if (!empty($tracksList)) : ?>
        <div class="row music-row my-1 text-center">
            <div class="col-4 col-lg-2 col-xl-2 d-flex justify-content-evenly align-items-center px-2 py-2">
                <span>Action</span>
            </div>
            <div class="col-7 col-lg-5 d-flex flex-column justify-content-center">
                <div class="track-title text-light">Titre</div>
                <div class="track-artists text-light">Artiste(s)</div>
            </div>
            <div class="col-md-1 d-none d-md-flex flex-column justify-content-center align-items-center">
                <span>Stats</span>
            </div>
            <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                <span class="d-lg-flex align-items-lg-center text-center">BPM</span>
            </div>
            <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                <span class="d-lg-flex align-items-lg-center">Clé</span>
            </div>
            <div class="col-lg-2 d-none d-lg-flex justify-content-center">
                <span class="d-lg-flex align-items-lg-center">Date de sortie</span>
            </div>
        </div>
        <?php foreach ($tracksList as $track) : ?>
            <div id="music-table" class="row music-row my-1">
                <div class="col-4 col-lg-2 col-xl-2 d-flex justify-content-evenly align-items-center px-2 py-2">
                    <i class="material-icons category-table-icon mdi mdi-play-circle-outline"
                       data-player-path="<?= $track->getHash() ?>"></i>
                    <?php if (UserHelper::isAuthAsAnyRole()) : ?>
                        <a href="<?= AltoRouter::getRouterInstance()->generate('downloadTrack', ['hash' => $track->getHash()]) ?>">
                            <i class="material-icons category-table-icon mdi mdi-download-box-outline"></i>
                        </a>
                    <?php else: ?>
                        <i class="material-icons category-table-icon mdi mdi-download-box-outline forbidDownloadNotConnected"></i>
                    <?php endif; ?>
                    <?php if (!isset($downloadsList)) : ?>
                        <i class="material-icons category-table-icon mdi mdi-playlist-plus addToPlaylist"
                           data-hash="<?= $track->getHash() ?>" <?= isset($_SESSION['user']['selectedDownloadListId']) ? 'data-playlist="' . $_SESSION['user']['selectedDownloadListId'] . '"' : '' ?>></i>
                    <?php endif; ?>
                </div>
                <div class="col-7 col-lg-5 d-flex flex-column justify-content-center">
                    <div class="track-title text-light"><?= $track->getTitle() ?></div>
                    <div class="track-artists text-light"><?= $track->getArtistsName() ?></div>
                </div>
                <div class="col-md-1 d-none d-md-flex flex-column justify-content-center align-items-center">
                    <span>
                        <i class="material-icons category-table-icon-stats mdi mdi-file-download"
                           title="Nombre de téléchargements"></i>
                        <?= $track->getDownloadCount() ?>
                    </span>
                    <span>
                        <i class="material-icons category-table-icon-stats mdi mdi-play"
                           title="Nombre d'écoutes"></i>
                        <?= $track->getListenCount() ?>
                    </span>
                </div>
                <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                    <span class="d-lg-flex align-items-lg-center text-center"><?= $track->getBPM() ?> BPM</span>
                </div>
                <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                    <span class="d-lg-flex align-items-lg-center"><?= $track->getMusicKey() ?></span>
                </div>
                <div class="col-lg-2 d-none d-lg-flex justify-content-center">
                    <span class="d-lg-flex align-items-lg-center"><?= $track->getFormattedReleaseDate() ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="h2">Pas de musique trouvée avec cet recherche.</p>
    <?php endif; ?>
</div>
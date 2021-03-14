<div class="col-md-12 mt-5 px-4">
    <?php use Core\PaginationService;
    use Core\UserHelper;

    if (!empty($tracksList)) : ?>
        <?php foreach ($tracksList as $track) : ?>
            <div id="music-table" class="row music-row my-1">
                <div class="col-4 col-lg-2 col-xl-2 d-flex justify-content-evenly align-items-center px-2 py-2">
                    <i class="material-icons category-table-icon mdi mdi-play-circle-outline"
                       data-player-path="<?= $track->getHash() ?>"></i>
                    <?php if (!UserHelper::isAuthorizedToDownload()) : ?>
                        <i class="material-icons category-table-icon mdi mdi-download-box-outline forbidDownload"></i>
                    <?php elseif (UserHelper::isAuthAsAnyRole()) : ?>
                        <a href="<?= AltoRouter::getRouterInstance()->generate('downloadTrack', ['hash' => $track->getHash()]) ?>">
                            <i class="material-icons category-table-icon mdi mdi-download-box-outline"></i>
                        </a>
                    <?php else: ?>
                        <i class="material-icons category-table-icon mdi mdi-download-box-outline forbidDownloadNotConnected"></i>
                    <?php endif; ?>
                    <i class="material-icons category-table-icon mdi mdi-playlist-plus addToPlaylist"
                       data-id="<?= $track->getId() ?>"></i>
                </div>
                <div class="col-8 col-lg-6 d-flex flex-column justify-content-center">
                    <div class="track-title text-light"><?= $track->getTitle() ?></div>
                    <div class="track-artists text-light"><?= $track->getArtistsName() ?></div>
                </div>
                <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                    <span class="d-lg-flex align-items-lg-center text-center"><?= $track->getBPM() ?> BPM</span>
                </div>
                <div class="col-lg-1 d-none d-lg-flex justify-content-center">
                    <span class="d-lg-flex align-items-lg-center"><?= $track->getMusicKey()->getMusicKey() ?></span>
                </div>
                <div class="col-lg-2 d-none d-lg-flex justify-content-center">
                    <span class="d-lg-flex align-items-lg-center"><?= $track->getFormattedReleaseDate() ?></span>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="d-flex justify-content-center mt-4">
            <nav id="category-pagination">
                <ul class="pagination">
                    <?php foreach (PaginationService::getPagination() as $pageNumber) : ?>
                        <li class="page-item">
                            <a class="page-link" data-link="<?= AltoRouter::getRouterInstance()
                                                                          ->generate('ajaxGetCategory', ['slug' => $slug, 'currentPage' => $pageNumber]) ?>"><?= $pageNumber ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
    <?php else: ?>
        <?php if (PaginationService::getTotalPages() < PaginationService::getCurrentPage()) : ?>
            <p class="h2">Cet page n'existe pas</p>
        <?php else: ?>
            <p class="h2">Pas de musique disponible pour le moment.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
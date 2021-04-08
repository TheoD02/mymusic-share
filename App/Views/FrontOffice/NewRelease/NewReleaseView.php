<?php

use App\Models\Categories;
use App\Models\Tracks;
use Core\UserHelper;

$router = AltoRouter::getRouterInstance();
/** @var Categories|false $categoryInfo */
/** @var Tracks[]|false $tracksList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1 class="text-center">Les dernières nouveautés</h1>
                </div>
                <?php require APP_ROOT . 'App/Views/FrontOffice/parts/selectDownloadList.php' ?>
                <div id="category-music-container">
                    <div class="col-md-12 mt-5 px-md-4 pb-5">
                        <?php if (!empty($tracksList)) : ?>
                            <?php require APP_ROOT . 'App/Views/FrontOffice/MusicTable.php' ?>
                        <?php else: ?>
                            <p class="h2">Pas de musique disponible pour le moment.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
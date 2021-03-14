<?php

use App\Models\Categories;
use App\Models\Tracks;

$router = AltoRouter::getRouterInstance();
/** @var Categories|false $categoryInfo */
/** @var Tracks[]|false $tracksList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <h1 class="text-center"><?= isset($categoryInfo) ? $categoryInfo->getName() : $viewTitle ?></h1>
                </div>
                <div id="category-img-loader-container" class="w-100 text-center d-none">
                    <p class="h2">Chargement de la liste...</p>
                    <img src="/assets/img/loader.svg" id="category-img-loader" alt="Image de chargement" title="Image de chargement">
                </div>
                <div id="category-music-container" class="pb-3">
                    <?php require APP_ROOT . 'App/Views/Ajax/CategoryTable.php' ?>
                </div>
            </div>
        </div>
    </div>
</div>
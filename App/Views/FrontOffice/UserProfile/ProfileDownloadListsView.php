<?php

use App\Models\UsersDownloadLists;
use Core\CSRFHelper;

/** @var UsersDownloadLists[] $userDownloadList */
/** @var UsersDownloadLists $listInfo */
?>
<div class="container mt-5">
    <?php require 'parts/nav.php' ?>
    <div class="row">
        <div class="col-md-12 mt-5">
            <div class="row">
                <div class="form-floating d-flex col-6">
                    <form action="<?= AltoRouter::getRouterInstance()->generate('profileDownloadListsDeleteAction') ?>" method="POST" class="d-flex">
                        <select class="form-select" name="downloadsListId" id="downloadsListId">
                            <option value="default" disabled selected>Sélectionner une liste de téléchargement</option>
                            <?php foreach ($downloadsList as $downloadListInfo): ?>
                                <option value="<?= $downloadListInfo->getId() ?>" <?= $downloadListId || $_SESSION['user']['selectedDownloadListId'] == $downloadListInfo->getId() ? 'selected' : '' ?>><?= $downloadListInfo->getName() ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn btn-primary mx-1" title="Ajouter une liste de téléchargement" name="addDownloadList" type="button">
                            +
                        </button>
                        <?php if (!empty($downloadsList)) : ?>
                            <?= CSRFHelper::generateCsrfHiddenInput() ?>
                            <button class="btn btn-primary mx-1" title="Supprimer la liste de téléchargement" name="removeDownloadListAction">
                                -
                            </button>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="form-floating d-flex flex-row-reverse col-6">
                    <form action="<?= AltoRouter::getRouterInstance()->generate('downloadListZip') ?>" method="POST">
                        <?= CSRFHelper::generateCsrfHiddenInput() ?>
                        <input type="hidden" name="downloadListId" id="downloadListId"
                               value="<?= $_SESSION['user']['selectedDownloadListId'] ?? 0 ?>">
                        <button class="btn btn-primary mx-1" title="Ajouter une liste de téléchargement" name="downloadListAction">
                            Télécharger la liste
                        </button>
                    </form>

                </div>
            </div>

            <div class="col-md-12 mt-4">
                <div id="download-list-table-container">
                    <?php if (!empty($tracksList)) : ?>
                        <?php require APP_ROOT . 'App/Views/FrontOffice/MusicTable.php' ?>
                    <?php else: ?>
                        <h1>Veuillez ajoutez des musiques.</h1>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

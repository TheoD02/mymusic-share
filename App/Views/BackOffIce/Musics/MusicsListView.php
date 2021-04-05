<?php

use App\Models\Tracks;
use Core\CSRFHelper;
use Core\PaginationService;

/** @var Tracks[]|false $tracksList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Liste des musiques</h1>
            <div class="d-flex flex-row-reverse my-3">
                <form action="<?= AltoRouter::getRouterInstance()->generate('adminUploadMusic') ?>" method="post">
                    <?= CSRFHelper::generateCsrfHiddenInput() ?>
                    <button type="submit" name="uploadMusic" class="btn btn-primary">
                        Ajouter une musique
                    </button>
                </form>
            </div>
            <table class="table table-striped table-dark text-center">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Artiste(s)</th>
                        <th>Date de sortie</th>
                        <th>Status</th>
                        <th>Catégorie</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($tracksList)) : ?>
                        <?php foreach ($tracksList as $track) : ?>
                            <tr>
                                <td><?= $track->getTitle() ?></td>
                                <td><?= $track->getArtistsName() ?></td>
                                <td><?= $track->getFormattedReleaseDate() ?></td>
                                <td><?= $track->isPending() ? '<i class="mdi mdi-file-clock text-warning" title="En attente de vérification pour la mise en ligne">' : '<i class="mdi mdi-check-circle text-success" title="Disponible en ligne"></i>' ?></td>
                                <td><?= $track->getCategoryName() ?></td>
                                <td>
                                    <form action="<?= AltoRouter::getRouterInstance()->generate('adminEditMusic', ['id' => $track->getId()]) ?>"
                                          method="POST">
                                        <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                        <button type="submit" name="editMusic">
                                            <i class="mdi mdi-file-edit-outline"></i>
                                        </button>
                                    </form>
                                    <form action="<?= AltoRouter::getRouterInstance()->generate('adminMusicDelete', ['id' => $track->getId()]) ?>"
                                          method="POST">
                                        <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                        <button type="submit" name="deleteMusicAction">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Pas de musique en ligne</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation example" class="my-5">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" href="<?= AltoRouter::getRouterInstance()
                                                                 ->generate(AltoRouter::getRouterInstance()
                                                                                      ->match()['name'], ['pageNumber' => 1]) ?>"><?= 1 ?></a>
                    </li>
                    <?php foreach (PaginationService::getPagination() as $pageNumber) : ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= AltoRouter::getRouterInstance()
                                                                     ->generate(AltoRouter::getRouterInstance()
                                                                                          ->match()['name'], ['pageNumber' => $pageNumber]) ?>"><?= $pageNumber ?></a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (PaginationService::getTotalPages()  > 1) : ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= AltoRouter::getRouterInstance()
                                                                     ->generate(AltoRouter::getRouterInstance()
                                                                                          ->match()['name'], ['pageNumber' => PaginationService::getTotalPages()]) ?>"><?= PaginationService::getTotalPages() ?></a>
                        </li>
                    <?php endif; ?>
            </nav>
        </div>
    </div>
</div>
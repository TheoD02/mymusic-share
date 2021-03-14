<?php

use App\Models\Artists;

/** @var Artists|false $artistList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Gestion des artistes</h1>
            <div class="d-flex flex-row-reverse my-3">
                <form action="<?= AltoRouter::getRouterInstance()->generate('adminAddArtist') ?>" method="post">
                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                    <button type="submit" name="addArtist" class="btn btn-primary">
                        Ajouter un artiste
                    </button>
                </form>
            </div>
            <table class="table table-striped table-dark text-center">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($artistList as $artist): ?>
                        <tr class="align-middle">
                            <td><?= $artist->getName() ?></td>
                            <td>
                                <form action="<?= AltoRouter::getRouterInstance()->generate('adminEditArtist', ['id' => $artist->getId()]) ?>"
                                      method="post">
                                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                    <button type="submit" name="editArtist">
                                        <i class="mdi mdi-folder-edit admin-icon"></i>
                                    </button>
                                </form>
                                <form action="<?= AltoRouter::getRouterInstance()->generate('adminDeleteArtist', ['id' => $artist->getId()]) ?>"
                                      method="post">
                                    <?= \Core\CSRFHelper::generateCsrfHiddenInput() ?>
                                    <button type="submit" name="deleteArtistAction">
                                        <i class="mdi mdi-delete admin-icon"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
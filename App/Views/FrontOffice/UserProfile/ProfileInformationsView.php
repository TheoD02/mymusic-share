<?php

use App\Models\Users;
use Core\CSRFHelper;

/** @var Users|false $userInfo */
?>
<div class="container mt-5">
    <?php require 'parts/nav.php' ?>
    <div class="row">
        <div class="col-md-12">
            <?php if (!empty($userInfo)) : ?>
                <div class="col-md-6 offset-md-3 py-3">
                    <p class="h4">Information personnelles : </p>
                    <p>Nom : <?= $userInfo->getLastname() ?></p>
                    <p>Prénom : <?= $userInfo->getFirstName() ?></p>
                </div>
                <div class="col-md-6 offset-md-3">
                    <p class="h4">Adresse : </p>
                    <p>Adresse : <?= $userInfo->getHouseNumber() ?> <?= $userInfo->getAddress() ?></p>
                    <p>Ville : <?= $userInfo->getCity() ?></p>
                    <p>Code postale : <?= $userInfo->getZipCode() ?></p>
                    <p>Pays : <?= $userInfo->getCountry() ?></p>
                    <form action="<?= AltoRouter::getRouterInstance()->generate('editProfilInformations') ?>" method="POST">
                        <?= CSRFHelper::generateCsrfHiddenInput() ?>
                        <div class="form-group">
                            <input type="submit" name="editProfilInformations" value="Modifier mes informations" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <p class="h2">Aucune informations disponible, si le problème persiste
                    <a href="<?= AltoRouter::getRouterInstance()->generate('contact') ?>">cliquez ici pour nous contactez</a>.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

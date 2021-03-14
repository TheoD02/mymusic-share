<?php

use App\Models\Orders;

/** @var Orders|false $activeSubscription */
?>
<div class="container mt-5">
    <?php require 'parts/nav.php' ?>
    <div class="row">
        <div class="col-md-12">
            <?php if ($activeSubscription) : ?>
                <p>Vous êtes actuellement abonné !</p>
                <p>Vous avez souscrit le : <?= $activeSubscription->getFormattedOrderDate() ?> a l'abonnement "<?= $activeSubscription->getSubscription()->getName() ?>" pour une durée
                   de <?= $activeSubscription->getSubscription()->getDuration() ?> mois, votre abonnement expire le : <?= $activeSubscription->getSubscriptionExpirationDate() ?></p>
                <p>Vous avez le droit à <?= $activeSubscription->getSubscription()->getNumberOfDownload() ?> téléchargement au total.</p>
            <?php else: ?>
                <p>Vous n'avez pas d'abonnement en cours</p>
            <?php endif; ?>
            <a href="<?= AltoRouter::getRouterInstance()->generate('profileOrdersHistory') ?>">
                Voir votre historique de commandes
            </a>
        </div>
    </div>
</div>
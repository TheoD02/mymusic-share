<?php

use App\Models\Orders;

/** @var Orders[]|false $userOrdersList */
?>
<div class="container mt-5">
    <?php require 'parts/nav.php' ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-dark text-center mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Numéro de commande</th>
                        <th>Date d'achat</th>
                        <th>Date de livraison</th>
                        <th>Actif</th>
                        <th>Nom de l'abonnement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($userOrdersList)) : ?>
                        <?php foreach ($userOrdersList as $orderInfo) : ?>
                            <tr>
                                <td><?= $orderInfo->getId() ?></td>
                                <td><?= $orderInfo->getNumber() ?></td>
                                <td><?= $orderInfo->getFormattedOrderDate() ?></td>
                                <td><?= $orderInfo->getFormattedDeliveryDate() ?></td>
                                <td><?= $orderInfo->isActive() ?></td>
                                <td><?= $orderInfo->getSubscription()->getName() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Aucune commande à afficher.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

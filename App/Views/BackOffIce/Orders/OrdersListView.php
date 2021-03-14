<?php

use App\Models\Orders;

/** @var Orders[]|false $ordersList */
?>
<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="text-center">Liste des commandes</h1>
            <table class="table table-striped table-dark text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom et prénom</th>
                        <th>Date de commande</th>
                        <th>Date de livraison</th>
                        <th>Offre</th>
                        <th>Active</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ordersList)) : ?>
                        <?php foreach ($ordersList as $order) : ?>
                            <tr class="align-middle">
                                <td><?= $order->getNumber() ?></td>
                                <td>
                                    <?= $order->getUser()->getLastName() . ' ' .
                                    $order->getUser()->getFirstName() ?>
                                </td>
                                <td><?= $order->getFormattedOrderDate() ?></td>
                                <td><?= $order->getFormattedDeliveryDate() ?></td>
                                <td><?= $order->getSubscription()->getName() ?></td>
                                <td>
                                    <i class="mdi mdi-folder-edit admin-icon"></i>
                                    <i class="mdi mdi-delete admin-icon"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">Pas de commandes à afficher</td>
                        </tr>
                    <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
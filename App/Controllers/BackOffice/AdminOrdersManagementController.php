<?php

namespace App\Controllers\BackOffice;

use App\Models\Orders;
use Core\Base\BaseAdminController;
use Core\Base\BaseView;

class AdminOrdersManagementController extends BaseAdminController
{
    /**
     * Affiche la liste des commandes
     *
     * @throws \Exception
     */
    public function showOrdersList(): void
    {
        $ordersList = (new Orders())->getOrdersList();
        $this->render('Orders/OrdersList', 'Liste des commandes', [
            'ordersList' => $ordersList,
        ], BaseView::BACK_OFFICE_PATH);
    }
}
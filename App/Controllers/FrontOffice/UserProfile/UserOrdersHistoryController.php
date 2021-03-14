<?php


namespace App\Controllers\FrontOffice\UserProfile;

use App\Models\Orders;
use Core\Base\BaseController;

class UserOrdersHistoryController extends BaseController
{
    public function showOrdersHistory(): void
    {
        $userOrdersList = (new Orders())->getOrdersList();
        $this->render('UserProfile/ProfileOrdersHistory', 'Abonnement en cours', [
            'userOrdersList' => $userOrdersList,
        ]);
    }
}
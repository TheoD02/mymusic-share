<?php


namespace App\Controllers\FrontOffice\UserProfile;


use App\Models\Orders;
use Core\Base\BaseController;
use Core\UserHelper;

class UserCurrentSubscriptionController extends BaseController
{
    public function showCurrentSubscription(): void
    {
        $activeSubscription = (new Orders())->setIdUsers(UserHelper::getUserID())->getCurrentSubscription();
        $this->render('UserProfile/ProfileCurrentSubscription', 'Abonnement en cours', [
            'activeSubscription' => $activeSubscription,
        ]);
    }
}
<?php


namespace App\Controllers\FrontOffice\UserProfile;

use Core\Base\BaseController;

class UserDownloadListsController extends BaseController
{
    public function showDownloadLists(): void
    {
        $this->render('UserProfile/ProfileDownloadLists', 'Abonnement en cours');
    }
}
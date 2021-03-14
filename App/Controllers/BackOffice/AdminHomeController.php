<?php

namespace App\Controllers\BackOffice;

use Core\Base\BaseAdminController;
use Core\Base\BaseView;

class AdminHomeController extends BaseAdminController
{
    public function index(): void
    {
        $this->render('AdminHome', 'Accueil - Admin', [], BaseView::BACK_OFFICE_PATH);
    }
}
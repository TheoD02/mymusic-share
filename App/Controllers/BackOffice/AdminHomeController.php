<?php

namespace App\Controllers\BackOffice;

use Core\Base\BaseAdminController;
use Core\Base\BaseView;

class AdminHomeController extends BaseAdminController
{
    /**
     * Affiche l'accueil de la page admin
     */
    public function index(): void
    {
        $this->render('AdminHome', 'Accueil - Admin', [], BaseView::BACK_OFFICE_PATH);
    }
}
<?php


namespace App\Controllers\FrontOffice;


use Core\Base\BaseController;

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->render('Home', 'Accueil');
    }
}
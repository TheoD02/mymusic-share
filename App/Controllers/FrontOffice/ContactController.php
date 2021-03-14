<?php


namespace App\Controllers\FrontOffice;


use Core\Base\BaseController;

class ContactController extends BaseController
{
    public function showContactForm(): void
    {
        $this->render('Contact', 'Nous contactez');
    }

    public function contactAction(): void
    {

    }
}
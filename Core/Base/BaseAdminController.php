<?php


namespace Core\Base;


use Core\FlashMessageService;
use Core\UserHelper;

class BaseAdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        if (!UserHelper::isAuthAsAdmin())
        {
            FlashMessageService::addErrorMessage('Vous n\'avez pas le droit d\'accéder à cette page.');
            $this->redirectWithAltoRouter('home');
        }
    }
}
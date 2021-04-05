<?php

namespace App\Controllers;

use App\Models\Roles;
use App\Models\Users;

class ErrorController
{
    /** 404 Error */
    public function notFound()
    {
        echo '404';
    }
}
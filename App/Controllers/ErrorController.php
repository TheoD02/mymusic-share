<?php

namespace App\Controllers;

use App\Models\Roles;
use App\Models\Users;

class ErrorController
{
    public function notFound()
    {
        echo '404';
    }
}
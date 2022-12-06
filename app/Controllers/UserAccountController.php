<?php declare(strict_types=1);

namespace App\Controllers;

use App\Template;

class UserAccountController
{
    public function showAccount():Template
    {
        return new Template('account.twig');
    }
}
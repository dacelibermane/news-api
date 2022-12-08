<?php declare(strict_types=1);

namespace App\Controllers;

use App\Redirect;
use App\Template;
use App\Validation;

class LoginController
{
    public function showForm(): Template
    {
        return new Template('login.twig');
    }

    public function login(): Redirect
    {
        $validation = new Validation();
        $validation->validateLogin($_POST);
        if(count($_SESSION['errors']) > 0){
            return new Redirect('/login');
        }
        $validation->getUserId($_POST);

        return new Redirect('/account');
    }
}
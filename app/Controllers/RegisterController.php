<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\RegisterService;
use App\Services\RegisterServiceRequest;
use App\Template;
use App\Redirect;
use App\Validation;

class RegisterController
{
    public function showForm(): Template
    {
        return new Template('register.twig');
    }

    public function register():Redirect
    {
        $validation = new Validation();
        $validation->validateRegistration($_POST);

        if(count($_SESSION['errors']) > 0){
            return new Redirect('/register');
        }

        $registerService = new RegisterService();
        $registerService->execute(
            new RegisterServiceRequest(
                $_POST['name'],
                $_POST['email'],
                $_POST['password']
            )
        );
        return new Redirect('/login');
    }
}
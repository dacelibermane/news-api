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
        if ($_POST['password'] !== $_POST['confirm_password']){
            $_SESSION['errors']['password'] = 'Passwords do not match!';
        }
        if(strlen($_POST['password']) < 7){
            $_SESSION['errors']['password'] = "Password must be at least 7 characters long";
        }
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
        return new Redirect('/register');
    }
}
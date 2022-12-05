<?php declare(strict_types=1);

namespace App\Controllers;
use App\Services\RegisterService;
use App\Template;

class LoginController
{

    public function showForm(): Template
    {
        return new Template('login.twig');
    }

    public function validateUser(): Template
    {
        $userEmail = (new RegisterService())->isUserRegistered($_POST['email']);
        $userId = (new RegisterService())->userId($_POST['email']);
        $userName = (new RegisterService())->userName($userId);
        $userPassword = (new RegisterService())->userPassword($userId);

        $_SESSION['user'] = $userName;
//        var_dump($userId);

        if(empty($userEmail)){
            return new Template('login.twig', ['error' => 'You are not a registered user. Please register!']);
        }
        if(!password_verify($_POST['password'], $userPassword)){
            return new Template('login.twig', ['error' => 'Invalid password!']);
        }

        return new Template('userAccount.twig', ['success' => 'Welcome ','user' => $_SESSION['user']]);
    }
}
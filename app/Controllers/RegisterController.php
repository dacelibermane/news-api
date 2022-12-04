<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\RegisterService;
use App\Services\RegisterServiceRequest;
use App\Template;

class RegisterController
{

    public function showForm(): Template
    {
        return new Template('register.twig');
    }

    public function register():Template
    {
        $hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $registerService = new RegisterService();
        $userEmail = $registerService->isUserRegistered($_POST['email']);

        if ($_POST['password'] !== $_POST['confirm_password']) {
            return new Template('register.twig', ['error' => 'Passwords do not match!']);
        }
        if ($userEmail != null) {
            return new Template('register.twig', ['error' => ' You are already registered. Please login!']);
        }
            $registerService->execute(
                new RegisterServiceRequest(
                    $_POST['name'],
                    $_POST['email'],
                    $hashedPassword
                )
            );
        return new Template('register.twig', ['success' => 'you are now registered! Please log in!']);
    }
}
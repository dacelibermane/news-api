<?php declare(strict_types=1);

namespace App;

use App\RegisterFormData;

class Validation
{
    public function validateRegistration(RegisterFormData $userData):RegisterFormData
    {
        if ($_POST['password'] !== $_POST['confirm_password']){
            $_SESSION['errors']['password'] = 'Passwords do not match!';
        }
        if(strlen($_POST['password']) < 7){
            $_SESSION['errors']['password'] = "Password must be at least 7 characters long";
        }
    }

}

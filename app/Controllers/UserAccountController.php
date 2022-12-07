<?php declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\Template;

class UserAccountController
{
    public function showAccount():Template
    {
        return new Template('account.twig');
    }

    public function changeEmail():Redirect
    {
        $queryBuilder = (Database::getConnection())->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchAssociative();

        $validPassword = password_verify($_POST['password_old'], $user['password']) ;
        if(!$validPassword){
            $_SESSION['errors']['old_password_match'] = "Invalid old password";
        }
        if ($_POST['password_new'] !== $_POST['password_confirm']) {
            $_SESSION['errors']['password_repeat'] = "Passwords do not match";
        }

        if (count($_SESSION['errors']) > 0) {
            return new Redirect('/login');
        }
        return new Redirect('/account');
    }
}
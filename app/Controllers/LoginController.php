<?php declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\Template;

class LoginController
{
    public function showForm(): Template
    {
        return new Template('login.twig');
    }

    public function login(): Redirect
    {
        $queryBuilder = (Database::getConnection())->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->fetchAssociative();


        if ($_POST['email'] !== $user['email']) {
            $_SESSION['errors']['email'] = "Wrong email";
        }

        $validPassword = password_verify($_POST['password'], $user['password']) ;
        if ($_POST['email'] === $user['email'] && !$validPassword) {
            $_SESSION['errors']['password_match'] = "Invalid password";
        }

        if (count($_SESSION['errors']) > 0) {
            return new Redirect('/login');
        }

        if($validPassword) {
            $_SESSION['auth_id'] = $user['id'];
        }
        return new Redirect('/account');

    }
}
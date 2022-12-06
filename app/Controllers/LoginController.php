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
            ->select('id, name, email, password')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])->fetchAssociative();

//        var_dump($user);die;
            $_SESSION['auth_id']['name'] = $user['name'];
        if ($_POST['email'] !== $user['email']) {
            $_SESSION['errors']['email'] = "Wrong email";
        }
//
//        $validPassword = password_verify($_POST['password'], $user['password']) ;
//        if (!$validPassword) {
//            $_SESSION['errors']['password_match'] = "Invalid password";
//        }

        if (count($_SESSION['errors']) > 0) {
            return new Redirect('/login');
        }
        return new Redirect('/account');
    }
}
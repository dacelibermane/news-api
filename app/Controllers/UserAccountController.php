<?php declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\Services\UpdateProfileService;
use App\Template;
use App\Validation;

class UserAccountController
{
    public function showAccount():Template
    {
        return new Template('account.twig');
    }

    public function changeEmail():Redirect{
        $validation = new Validation();
        $validation->validateName($_POST);

        if (count($_SESSION['errors']) > 0) {
            return new Redirect('/account');
        }

        (new UpdateProfileService())->execute('email', $_POST['email'], $_SESSION['auth_id']);
        return new Redirect('/account');
    }

    public function changePassword():Redirect
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
            return new Redirect('/account');
        }

        $newPassword = password_hash($_POST['password_new'], PASSWORD_DEFAULT);
        $id = $_SESSION['auth_id'];
         (new UpdateProfileService())->execute('password',$newPassword,$id);
        return new Redirect('/account');
    }
}
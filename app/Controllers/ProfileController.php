<?php declare(strict_types=1);

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\Services\UpdateProfileService;
use App\Template;
use App\Validation;

class ProfileController
{
    public function showProfile():Template
    {
        return new Template('profile.twig');
    }

    public function updateCredentials():Redirect
    {
        if(!empty($_POST['email'])){
            $this->changeEmail();
        }
        $_SESSION['errors']['email'] = 'Email is required';
        return new Redirect('profile');
    }

    public function updatePassword():Redirect
    {
        if(!empty($_POST['password'])){
            $this->changePassword();
        }
        $_SESSION['errors']['old_password'] = 'Password is required';
        return new Redirect('profile');
    }

    public function changeEmail(): Redirect
    {
        $queryBuilder = (Database::getConnection())->createQueryBuilder();
        $userData = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION["auth_id"])
            ->fetchAssociative();

        $emailExists= $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $_POST['email'])
            ->fetchAssociative();

        if ($userData['email'] !== $_POST['email'] && $emailExists) {
            $_SESSION['errors']['email'] = 'Email address is taken!';
        }
        if(empty($_POST['email'])){
            $_SESSION['errors']['email'] = 'Email is required!';
        }
        if(count($_SESSION['errors']) > 0){
            return new Redirect('/profile');
        }

        $newValue = $_POST['email'];
        $id = $_SESSION["auth_id"];
        $updateUserService = new UpdateProfileService();
        $updateUserService->execute('email', $newValue, $id);
        $_SESSION['success']['email'] = 'Your email was successfully updated';
        return new Redirect('/profile');
    }
    public function changePassword():Redirect
    {
        $queryBuilder = (Database::getConnection())->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('id = ?')
            ->setParameter(0, $_SESSION['auth_id'])
            ->fetchAssociative();

        $validPassword = password_verify($_POST['old_password'], $user['password']) ;
        if(!$validPassword){
            $_SESSION['errors']['old_password'] = "Invalid old password";
        }
        if ($_POST['password_new'] !== $_POST['password_confirm']) {
            $_SESSION['errors']['password_new'] = "Passwords do not match";
        }

        if (count($_SESSION['errors']) > 0) {
            return new Redirect('/profile');
        }

        $newPassword = password_hash($_POST['password_new'], PASSWORD_DEFAULT);
        $id = $_SESSION['auth_id'];
         (new UpdateProfileService())->execute('password',$newPassword,$id);
         $_SESSION['success']['password'] = 'Your password was successfully updated';
        return new Redirect('/profile');
    }
}
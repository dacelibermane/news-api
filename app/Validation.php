<?php declare(strict_types=1);

namespace App;


class Validation
{
    public function validateRegistration(array $post): void
    {
        $this->validateName($post);
        $this->validateEmail($post);
        $this->validatePassword($post);
    }

    public function validateName(array $post): void
    {
        if (empty($post['name'])) {
            $_SESSION['errors']['name'] = 'Name is required';
        }
        if (strlen($post['name']) < 3) {
            $_SESSION['errors']['name'] = 'Name must be at least 3 characters';
        }
    }

    public function validateEmail(array $post): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $post['email'])
            ->fetchAssociative();
        if ($user) {
            $_SESSION['errors']['email'] = 'Email already exists';
        }

        if (empty($post['email'])) {
            $_SESSION['errors']['email'] = 'Email is required';
        }
    }

    public function validatePassword(array $post): void
    {
        if ($post['password'] !== $post['confirm_password']) {
            $_SESSION['errors']['password'] = 'Passwords do not match';
        }
        if (empty($post['password'])) {
            $_SESSION['errors']['password'] = 'Password is required';
        }
    }

    public function validateLogin(array $post): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $post['email'])
            ->fetchAssociative();


            if (!$user) {
                $_SESSION['errors']['email'] = "Wrong email";
            }
            if ($user &&!password_verify($_POST['password'], $user['password'])) {
                $_SESSION['errors']['password'] = "Wrong password";
            }

    }
    public function getUserId(array $post):void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $user = $queryBuilder
            ->select('*')
            ->from('users')
            ->where('email = ?')
            ->setParameter(0, $post['email'])
            ->fetchAssociative();

        $_SESSION['auth_id'] = $user['id'];
    }

}
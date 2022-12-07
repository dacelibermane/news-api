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
    }
}
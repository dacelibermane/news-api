<?php declare(strict_types=1);

namespace App\Services;

use App\Database;

class UpdateProfileService
{
    public function execute( string $newValue, string $id): void
    {
        $queryBuilder = Database::getConnection()->createQueryBuilder();
        $queryBuilder
            ->update('users')
            ->set('email', $newValue)
            ->where('id = ?')
            ->setParameter(0, $id)
            ->executeQuery();
    }
}

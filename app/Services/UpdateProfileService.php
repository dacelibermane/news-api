<?php declare(strict_types=1);

namespace App\Services;

use App\Database;

class UpdateProfileService
{
    public function execute(string $type, string $newValue, string $id): void
    {
        $connection = Database::getConnection();
        $connection->executeQuery("UPDATE users SET {$type} = '{$newValue}' WHERE id = '{$id}'");
    }
}

<?php declare(strict_types=1);

namespace App\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class RegisterService
{
    private Connection $connection;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => 'news_api',
            'user' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ];
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public function execute(RegisterServiceRequest $request): void
    {
        $this->connection->insert('users', [
            'name' => $request->getName(),
            'email' => $request->getEmail(),
            'password' => $request->getPassword()
        ]);
    }

    public function isUserRegistered(string $email): ?RegisterServiceRequest
    {
        $checkUser = $this->connection->executeQuery("SELECT * FROM users WHERE email = '{$email}'")->fetchAssociative();

        if (!$checkUser) {
            return null;
        } else {
            return new RegisterServiceRequest($checkUser['name'], $checkUser['email'], $checkUser['password']);
        }
    }

    public function userId(?string $email): ?string
    {
        $userId = $this->connection->executeQuery("SELECT id FROM users WHERE email = '{$email}'")->fetchOne();
        if (!$userId) {
            return null;
        } else {
            return $userId;
        }
    }

    public function userName(?string $id): ?string
    {
        $userName = $this->connection->executeQuery("SELECT name FROM users WHERE id = '{$id}'")->fetchOne();
        if (!$userName) {
            return null;
        } else {
            return $userName;
        }
    }

    public function userPassword(?string $id): ?string
    {
        $userId = $this->connection->executeQuery("SELECT password FROM users WHERE id = '{$id}'")->fetchOne();
        if (!$userId) {
            return null;
        } else {
            return $userId;
        }
    }
}
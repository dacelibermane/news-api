<?php declare(strict_types=1);

namespace App;

class RegisterFormData
{
    private string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmed;

    public function __construct(string $name, string $email, string $password, string $passwordConfirmed)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmed = $passwordConfirmed;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getPasswordConfirmed(): string
    {
        return $this->passwordConfirmed;
    }
}

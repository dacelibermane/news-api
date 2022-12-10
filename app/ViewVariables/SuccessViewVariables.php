<?php declare(strict_types=1);

namespace App\ViewVariables;

class SuccessViewVariables implements ViewVariables
{
    public function getName():string
    {
        return 'success';
    }

    public function getValue():array
    {
        return $_SESSION['success'] ?? [];
    }
}

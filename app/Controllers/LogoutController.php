<?php declare(strict_types=1);

namespace App\Controllers;

class LogoutController
{
   public function logout(){
       unset($_SESSION['auth_id']);
       header("Location: /");
   }
}

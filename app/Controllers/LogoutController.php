<?php declare(strict_types=1);

namespace App\Controllers;

class LogoutController
{
   public function logout(){
       session_destroy();
       header("Location: /");
   }
}

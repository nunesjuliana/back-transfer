<?php

namespace App\Http\Repositories;
use App\Http\Models\Usuario;

class UsuarioRepository
{
    public function findByEmail($email)
    {
       $user =  Usuario::where('email', '=', $email)->get();

       if(count($user) == 0)
         return null;

       return $user[0];
    }
}

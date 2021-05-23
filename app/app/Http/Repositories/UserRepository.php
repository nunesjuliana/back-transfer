<?php

namespace App\Http\Repositories;
use App\Http\Models\User;

class UserRepository
{
    public function findByMail($email)
    {
       $user =  User::where('email', '=', $email)->get();

       if(count($user) == 0)
         return null;

       return $user[0];
    }
}

<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\UserConstant;

class User extends Model
{

    protected $fillable = ['name','email','cpf','cnpj','tipouser','password','balance'];

    public function isShopkeeper()
    {
       return $this->tipouser == UserConstant::TIPOUSER_JURIDICO;
    }

    public function hasBalance($value){

        return $this->balance - $value < 0;
    }

    public function removeMoney($value){

        $this->balance -= $value;
    }

    public function putMoney($value){

        $this->balance += $value;
    }

}

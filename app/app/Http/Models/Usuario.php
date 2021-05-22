<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Constants\UsuarioConstant;

class Usuario extends Model
{

    protected $fillable = ['nome','email','cpf','cnpj','tipousuario','senha','saldocarteira'];

    public function isLojista()
    {
       return $this->tipousuario === UsuarioConstant::TIPOUSUARIO_JURIDICO;
    }

    public function temSaldoInsuficiente($valorAretirar){

        return $this->saldocarteira - $valorAretirar < 0;
    }

    public function saque($valor){

        $this->saldocarteira -= $valor;
    }

    public function deposito($valor){

        $this->saldocarteira += $valor;
    }

}

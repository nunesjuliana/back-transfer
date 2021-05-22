<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{

    public $pagador;
    public $beneficiario;

    public $email_pagador;
    public $email_beneficiario;

    public $valor;

    public function __construct(
        $pagador,
        $beneficiario,
        $email_pagador,
        $email_beneficiario,
        $valor)
    {
        $this->pagador = $pagador;
        $this->beneficiario = $beneficiario;
        $this->email_pagador = $email_pagador;
        $this->email_beneficiario = $email_beneficiario;
        $this->valor = $valor;
    }


}

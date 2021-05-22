<?php

namespace App\Http\Validations;

use App\Exceptions\ValidationUserException;

class UsuarioValidation
{

    public function transactionValid($payer, $value)
    {
        if ($payer->isLojista())
           throw new ValidationUserException("Lojista não podem realizar transferências", 422);

        if ($payer->temSaldoInsuficiente($value))
            throw new ValidationUserException("Usuário tem saldo insuficiente para a transferência", 404);

    }

    public function ExistsUser($user,$email)
    {
        if(!isset($user))
            throw new ValidationUserException("Não existe usuário com email {$email} no sistema.", 404);
    }

}

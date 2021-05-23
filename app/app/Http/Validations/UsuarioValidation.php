<?php

namespace App\Http\Validations;

use App\Exceptions\ValidationUserException;

class UsuarioValidation
{

    public function transactionValid($transacao)
    {
        if ($transacao->pagador->isLojista())
           throw new ValidationUserException("Lojista não podem realizar transferências", 422);

        if ($transacao->pagador->temSaldoInsuficiente($transacao->valor))
            throw new ValidationUserException("Usuário {$transacao->pagador->nome} tem saldo insuficiente para a transferência", 404);

    }

    public function ExistsUser($user,$mail)
    {
        if(!isset($user))
            throw new ValidationUserException("Não existe usuário com email {$mail} no sistema.", 404);
    }

}

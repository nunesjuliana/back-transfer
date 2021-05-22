<?php

namespace App\Http\Validations;

use App\Exceptions\ValidationUserException;

class UserValidation
{

    public function transactionValid($Transaction)
    {
        if ($Transaction->payer->isShopkeeper())
           throw new ValidationUserException("Lojista não podem realizar transferências", 401);

        if ($Transaction->payer->hasBalance($Transaction->value))
            throw new ValidationUserException("Usuário {$Transaction->payer->name} tem saldo insuficiente para a transferência", 402);

    }

    public function ExistsUser($user,$mail)
    {
        if(!isset($user))
            throw new ValidationUserException("Não existe usuário com email {$mail} no sistema.", 403);
    }

}

<?php

namespace App\Http\Validations;

use App\Exceptions\ValidationUserException;
use Illuminate\Http\Response;

class UserValidation
{

    public function transactionValid($Transaction)
    {

        if ($Transaction->payer->isShopkeeper()){
           throw new ValidationUserException("Lojista não podem realizar transferências", Response::HTTP_PRECONDITION_FAILED);
        }

        if ($Transaction->payer->hasBalance($Transaction->value))
            throw new ValidationUserException("Usuário {$Transaction->payer->name} tem saldo insuficiente para a transferência", Response::HTTP_PRECONDITION_FAILED);

    }

    public function ExistsUser($user,$mail)
    {
        if(!isset($user))
            throw new ValidationUserException("Não existe usuário com email {$mail} no sistema.", Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}

<?php

namespace App\Http\Validations;

use App\Exceptions\ValidationUserException;
use Illuminate\Http\Response;

class UserValidation
{

    public function transactionValid($Transaction)
    {

        if ($Transaction->getPayer()->isShopkeeper()){
           throw new ValidationUserException("Lojista não podem realizar transferências", Response::HTTP_PRECONDITION_FAILED);
        }

        if ($Transaction->getPayer()->hasNoBalance($Transaction->getValue()))
            throw new ValidationUserException("Usuário {$Transaction->getPayer()->name} tem saldo insuficiente para a transferência", Response::HTTP_PRECONDITION_FAILED);

    }

    public function ExistsUser($user,$mail)
    {
        if(!isset($user))
            throw new ValidationUserException("Não existe usuário com email {$mail} no sistema.", Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function validUsersToTransaction($Transaction)
    {
        $this->existsUser(
            $Transaction->getPayer(),
            $Transaction->getEmailPayer());

        $this->existsUser(
            $Transaction->getPayee(),
            $Transaction->getEmailPayee());

        $this->transactionValid($Transaction);
    }

}

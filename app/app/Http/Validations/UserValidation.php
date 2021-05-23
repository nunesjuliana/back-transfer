<?php

namespace App\Http\Validations;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class UserValidation
{

    public function ValidPayer($payer, $value)
    {

        if ($payer->isShopkeeper()){
           throw new CustomException("Lojista não podem realizar transferências", Response::HTTP_PRECONDITION_FAILED);
        }

        if ($payer->hasNoBalance($value))
            throw new CustomException("Usuário {$payer->name} tem saldo insuficiente para a transferência", Response::HTTP_PRECONDITION_FAILED);

    }

    public function ExistsUser($user,$mail)
    {
        if(!isset($user))
            throw new CustomException("Não existe usuário com email {$mail} no sistema.", Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function validUsersToTransaction($Transaction)
    {
        $this->existsUser(
            $Transaction->getPayer(),
            $Transaction->getEmailPayer());

        $this->existsUser(
            $Transaction->getPayee(),
            $Transaction->getEmailPayee());

        $this->ValidPayer($Transaction->getPayer(),$Transaction->getValue());
    }

}

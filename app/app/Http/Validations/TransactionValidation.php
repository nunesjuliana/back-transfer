<?php

namespace App\Http\Validations;

use App\Exceptions\CustomException;
use Illuminate\Http\Response;

class TransactionValidation
{
    public function validValueGreaterThanZero($value)
    {
        if ($value <= 0){
           throw new CustomException("Valor de transferencia deve ser maior que zero", Response::HTTP_PRECONDITION_FAILED);
        }

    }

}

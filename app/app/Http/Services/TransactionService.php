<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Validations\UserValidation;
use Illuminate\Support\Facades\DB;
use App\Http\Events\CompletedTransactionEvent;
use App\Http\Events\TransactionInProcessEvent;
use Exception;

class TransactionService
{

    public function __construct(
      UserRepository $UserRepository,
      UserValidation $UserValidation)
    {
       $this->UserRepository = $UserRepository;
       $this->UserValidation = $UserValidation;
    }

    private function validUsers($Transaction)
    {
        $this->UserValidation->existsUser(
            $Transaction->payer,
            $Transaction->email_payer);

        $this->UserValidation->existsUser(
            $Transaction->payee,
            $Transaction->email_payee);

        $this->UserValidation->transactionValid($Transaction);
    }

    public function processTransaction($Transaction)
    {
        DB::beginTransaction();
        try {

            $Transaction->payer = $this->UserRepository->findByMail($Transaction->email_payer);
            $Transaction->payee = $this->UserRepository->findByMail($Transaction->email_payee);

            $this->validUsers($Transaction);

            $Transaction->payer->removeMoney($Transaction->value);
            $Transaction->payee->putMoney($Transaction->value);
            $Transaction->payee->save();
            $Transaction->payer->save();

            event(new TransactionInProcessEvent($Transaction->payer,$Transaction->payee));

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        event(new CompletedTransactionEvent($Transaction->payer,$Transaction->payee));

        return response()->json(['mensagem' => 'Transação realizada com sucesso'], 200);

    }

}

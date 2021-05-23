<?php

namespace App\Http\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Validations\UserValidation;
use Illuminate\Support\Facades\DB;
use App\Http\Events\CompletedTransactionEvent;
use App\Http\Events\TransactionInProcessEvent;
use Exception;
use App\Http\Validations\TransactionValidation;

class TransactionService
{

    public function __construct(
      UserRepository $UserRepository,
      UserValidation $UserValidation,
      TransactionValidation $TransactionValidation)
    {
       $this->UserRepository = $UserRepository;
       $this->UserValidation = $UserValidation;
       $this->TransactionValidation = $TransactionValidation;
    }

    public function processTransaction($transaction)
    {
        DB::beginTransaction();
        try {

            $this->TransactionValidation->validValueGreaterThanZero($transaction->getValue());

            $transaction->setPayer($this->UserRepository->findByMail($transaction->getEmailPayer()));
            $transaction->setPayee($this->UserRepository->findByMail($transaction->getEmailPayee()));

            $this->UserValidation->validUsersToTransaction($transaction);

            $transaction->getPayer()->removeMoney($transaction->getValue());
            $transaction->getPayee()->putMoney($transaction->getValue());
            $transaction->getPayee()->save();
            $transaction->getPayer()->save();

            event(new TransactionInProcessEvent($transaction->getPayer(),$transaction->getPayee()));

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        event(new CompletedTransactionEvent($transaction->getPayer(),$transaction->getPayee()));

        return response()->json(['mensagem' => 'Transação realizada com sucesso'], 200);

    }

}

<?php

namespace App\Http\Services;

use App\Http\Repositories\UsuarioRepository;
use App\Http\Validations\UsuarioValidation;
use Illuminate\Support\Facades\DB;
use App\Http\Events\CompletedTransactionEvent;
use App\Http\Events\TransactionInProcessEvent;
use App\Exceptions\ValidationUserException;
use Exception;

class UsuarioService
{

    public function __construct(
      UsuarioRepository $usuarioRepository,
      UsuarioValidation $usuarioValidation)
    {
       $this->usuarioRepository = $usuarioRepository;
       $this->usuarioValidation = $usuarioValidation;
    }

    public function processTransaction($email_payer, $email_payee, $value)
    {
        DB::beginTransaction();
        try {

            $payer = $this->usuarioRepository->findByEmail($email_payer);
            $payee = $this->usuarioRepository->findByEmail($email_payee);

            $this->usuarioValidation->existsUser($payer,$email_payer);
            $this->usuarioValidation->existsUser($payee,$email_payee);
            $this->usuarioValidation->transactionValid($payer, $value);

            $payer->saque($value);
            $payee->deposito($value);
            $payee->save();
            $payer->save();

            event(new TransactionInProcessEvent($payer,$payee));

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
        event(new CompletedTransactionEvent($payer,$payee));

        return response()->json(['mensagem' => 'Transação realizada com sucesso'], 200);

    }

}

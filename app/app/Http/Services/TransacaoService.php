<?php

namespace App\Http\Services;

use App\Http\Repositories\UsuarioRepository;
use App\Http\Validations\UsuarioValidation;
use Illuminate\Support\Facades\DB;
use App\Http\Events\CompletedTransactionEvent;
use App\Http\Events\TransactionInProcessEvent;
use Exception;

class TransacaoService
{

    public function __construct(
      UsuarioRepository $usuarioRepository,
      UsuarioValidation $usuarioValidation)
    {
       $this->usuarioRepository = $usuarioRepository;
       $this->usuarioValidation = $usuarioValidation;
    }

    private function validUsers($transacao)
    {
        $this->usuarioValidation->existsUser(
            $transacao->pagador,
            $transacao->email_pagador);

        $this->usuarioValidation->existsUser(
            $transacao->beneficiario,
            $transacao->email_beneficiario);

        $this->usuarioValidation->transactionValid($transacao);
    }

    public function processTransaction($transacao)
    {
        DB::beginTransaction();
        try {

            $transacao->pagador = $this->usuarioRepository->findByMail($transacao->email_pagador);
            $transacao->beneficiario = $this->usuarioRepository->findByMail($transacao->email_beneficiario);

            $this->validUsers($transacao);

            $transacao->pagador->saque($transacao->valor);
            $transacao->beneficiario->deposito($transacao->valor);
            $transacao->beneficiario->save();
            $transacao->pagador->save();

            event(new TransactionInProcessEvent($transacao->pagador,$transacao->beneficiario));

            DB::commit();

        } catch (Exception $e){
            DB::rollBack();
            throw $e;
        }

        event(new CompletedTransactionEvent($transacao->pagador,$transacao->beneficiario));

        return response()->json(['mensagem' => 'Transação realizada com sucesso'], 200);

    }

}

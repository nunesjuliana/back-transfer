<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsuarioRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Services\TransacaoService;
use App\Http\Models\Transacao;
use Exception;

class TransacaoController extends Controller
{
    public function __construct(TransacaoService $transacaoService)
    {
        $this->transacaoService = $transacaoService;
    }


    public function transaction(Request $request)
    {
        $transacao = new Transacao(
            null,
            null,
            $request->payer,
            $request->payee,
            $request->value);

        $result = $this->transacaoService->processTransaction($transacao);

        return $result;

    }
}

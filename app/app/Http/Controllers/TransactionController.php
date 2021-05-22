<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Http\Services\TransactionService;
use App\Http\Models\Transaction;
use Exception;

class TransactionController extends Controller
{
    public function __construct(TransactionService $TransactionService)
    {
        $this->TransactionService = $TransactionService;
    }


    public function transaction(Request $request)
    {
        $Transaction = new Transaction(
            null,
            null,
            $request->payer,
            $request->payee,
            $request->value);

        $result = $this->TransactionService->processTransaction($Transaction);

        return $result;

    }
}

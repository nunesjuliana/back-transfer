<?php

namespace App\Http\Listeners;

use App\Http\Events\TransactionInProcessEvent;
use GuzzleHttp\Client as guzzClient;
use App\Http\Constants\TransactionConstant;
use App\Exceptions\ExternalsApisException;
use Exception;
use Illuminate\Http\Response;

class CheckAuthorizationListener
{
    /**
     * Handle the event.
     *
     * @param  TransactionInProcessEvent  $event
     * @return void
     */

    private function formatBodyRequest($payer, $payee)
    {
        return
        json_encode([
        "payer" => $payer,
        "payee" => $payee
        ]);
    }

    public function handle(TransactionInProcessEvent $event)
    {
       try{
            $client = new guzzClient();
            $body = $this->formatBodyRequest($event->getPayer(),$event->getPayee());
            $response = $client->get(TransactionConstant::URL_AUTORIZATION_TRANSACTION,
            [ 'body' => $body, 'http_errors' => false ]);
       }catch(Exception $e){
        throw new ExternalsApisException("Erro na api de autorizacao: {$e->getMessage()} ", Response::HTTP_INTERNAL_SERVER_ERROR);
       }

    }
}

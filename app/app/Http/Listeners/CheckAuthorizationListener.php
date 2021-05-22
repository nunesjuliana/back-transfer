<?php

namespace App\Http\Listeners;

use App\Http\Events\TransactionInProcessEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client as guzzClient;
use App\Http\Constants\TransactionConstant;
use Exception;

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

       }

    }
}

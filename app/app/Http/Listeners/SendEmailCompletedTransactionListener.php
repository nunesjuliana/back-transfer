<?php

namespace App\Http\Listeners;

use App\Http\Events\CompletedTransactionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client as guzzClient;
use App\Http\Constants\TransactionConstant;


class SendEmailCompletedTransactionListener implements ShouldQueue
{
    use InteractsWithQueue;

    private function formatBodyRequest($payer, $payee)
    {
        return
        json_encode([
        "payer" => $payer,
        "payee" => $payee,
        ]);
    }
    /**
     * Handle the event.
     *
     * @param  CompletedTransactionEvent  $event
     * @return void
     */

    public function handle(CompletedTransactionEvent $event)
    {
      try{
            $client = new guzzClient();
            $body = $this->formatBodyRequest($event->getPayer(),$event->getPayee());
            $response = $client->post(TransactionConstant::URL_NOTIFY_TRANSACTION,
                [ 'body' => $body, 'http_errors' => false ]);

      }catch(\Throwable $e){
          report($e);
      }

    }
}

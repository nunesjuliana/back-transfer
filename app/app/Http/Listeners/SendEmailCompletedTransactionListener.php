<?php

namespace App\Http\Listeners;

use App\Http\Events\CompletedTransactionEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client as guzzClient;
use App\Http\Constants\TransactionConstant;
use App\Exceptions\ExternalsApisException;
use Exception;

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

               throw new Exception("error na api", 422);

      }catch(\Throwable $e){
          throw new ExternalsApisException("Erro na api de envio de email: {$e->getMessage()} ", 500);
      }

    }
}

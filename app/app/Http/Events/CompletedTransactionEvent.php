<?php

namespace App\Http\Events;

use App\Http\Models\Usuario;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CompletedTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    private $payer;
    private $payee;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($payer, $payee)
    {
        $this->payer = $payer;
        $this->payee = $payee;
    }

    public function getPayer(){
        return $this->payer;
    }

    public function getPayee(){
        return $this->payee;
    }

}

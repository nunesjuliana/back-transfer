<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    public $payer;
    public $payee;

    public $email_payer;
    public $email_payee;

    public $value;

    public function __construct(
        $payer,
        $payee,
        $email_payer,
        $email_payee,
        $value)
    {
        $this->payer = $payer;
        $this->payee = $payee;
        $this->email_payer = $email_payer;
        $this->email_payee = $email_payee;
        $this->value = $value;
    }


}

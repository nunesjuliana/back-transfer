<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //@var User
    private $payer;

    //@var User
    private $payee;

    //@var string
    private $email_payer;
    //@var string
    private $email_payee;

    //@var float
    private $value;

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

    public function getPayer()
    {
        return $this->payer;
    }

    public function getPayee()
    {
        return $this->payee;
    }

    public function getEmailPayer()
    {
        return $this->email_payer;
    }

    public function getEmailPayee()
    {
        return $this->email_payee;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setPayer($payer)
    {
        $this->payer = $payer;
    }

    public function setPayee($payee)
    {
        $this->payee = $payee;
    }

    public function setEmailPayer($email_payer)
    {
        $this->email_payer = $email_payer;
    }

    public function setEmailPayee($email_payee)
    {
        $this->email_payee = $email_payee;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }


}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use App\Http\Constants\UserConstant;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    public function testCorretTransactionBetweenUsers()
    {

        $user1 = factory(\App\Http\Models\User::class)->create();

        $user2 = factory(\App\Http\Models\User::class)->create();

        $response = $this->put('/api/transaction',
         [
           "payer" => $user1->email,
           "payee" => $user2->email,
           "value" => 50
         ]);

        $response->assertStatus(Response::HTTP_OK);

    }

    public function testCorretTransactionBetweenUsersAndShopkeeper()
    {

        $user1 = factory(\App\Http\Models\User::class)->create();

        $user2 = factory(\App\Http\Models\User::class)->create(
            [
                "tipouser"  => UserConstant::TIPOUSER_JURIDICO,
                "cnpj"  => "84038259000111",
            ]
        );

        $response = $this->put('/api/transaction',
         [
           "payer" => $user1->email,
           "payee" => $user2->email,
           "value" => 50
         ]);

        $response->assertStatus(Response::HTTP_OK);

    }


    public function testErrorToSendMoneyByshopkeeperTransaction()
    {

        $payer = factory(\App\Http\Models\User::class)->create(
            [
                "tipouser"  => UserConstant::TIPOUSER_JURIDICO,
                "cnpj"  => "84038259000111",
            ]
        );

        $payee= factory(\App\Http\Models\User::class)->create();

        $response = $this->put('/api/transaction',
         [
           "payer" => $payer->email,
           "payee" => $payee->email,
           "value" => 50
         ]);

        $response->assertStatus(Response::HTTP_PRECONDITION_FAILED);

    }

    public function testErrorNoExistsusersTransaction()
    {

        $payer = factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"j@gmail.com",
            ]
        );

        $payee= factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"cst@gmail.com",
            ]
        );

        $response = $this->put('/api/transaction',
         [
           "payer" => 'carlos@gmail.com',
           "payee" => $payee->email,
           "value" => 50
         ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    }

    public function testErrorNoBalancetoTransactionTransaction()
    {

        $payer = factory(\App\Http\Models\User::class)->create(
            [
                "balance" => 30
            ]
        );

        $payee= factory(\App\Http\Models\User::class)->create();

        $response = $this->put('/api/transaction',
         [
           "payer" => $payer->email,
           "payee" => $payee->email,
           "value" => 50
         ]);

        $response->assertStatus(Response::HTTP_PRECONDITION_FAILED);

    }
}

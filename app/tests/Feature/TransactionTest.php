<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    public function testCorretTransactionBetweenUsers()
    {

        $user1 = factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"j@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "22222222222",
                "password"  => "123",
                "balance" => 100
            ]
        );

        $user2 = factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "password"  => "123",
                "balance" => 100
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

    public function testCorretTransactionBetweenUsersAndShopkeeper()
    {

        $user1 = factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "11111111111",
                "password"  => "123",
                "balance" => 100
            ]
        );

        $user2 = factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"j@gmail.com",
                "tipouser"  => "1",
                "cnpj"  => "22222222222222",
                "password"  => "123",
                "balance" => 100
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
                "email" =>"j@gmail.com",
                "tipouser"  => 1, //indicando que é lojista
                "cnpj"  => "22222222222222",
                "password"  => "123",
                "balance" => 100
            ]
        );

        $payee= factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "11111111111",
                "password"  => "123",
                "balance" => 100
            ]
        );

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
                "tipouser"  => "1", //indicando que é lojista
                "cnpj"  => "22222222222222",
                "password"  => "123",
                "balance" => 100
            ]
        );

        $payee= factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "11111111111",
                "password"  => "123",
                "balance" => 100
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
                "email" =>"j@gmail.com",
                "tipouser"  => "0", //indicando que é lojista
                "cpf"  => "22222222222",
                "password"  => "123",
                "balance" => 30
            ]
        );

        $payee= factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "11111111111",
                "password"  => "123",
                "balance" => 100
            ]
        );

        $response = $this->put('/api/transaction',
         [
           "payer" => $payer->email,
           "payee" => $payee->email,
           "value" => 50
         ]);

        $response->assertStatus(Response::HTTP_PRECONDITION_FAILED);

    }
}

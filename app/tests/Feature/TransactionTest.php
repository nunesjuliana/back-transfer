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

        $user1 = factory(\App\Http\Models\User::class)->create(
            [
                "balance" => 100
            ]
        );

        $user2 = factory(\App\Http\Models\User::class)->create(
            [
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

        //Testing transaction results
        $response = $this->get("/api/user/$user1->id")
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonFragment(
        [
           'balance' => '50',
        ]);

        $response = $this->get("/api/user/$user2->id")
        ->assertStatus(Response::HTTP_OK)
        ->assertJsonFragment(
        [
           'balance' => '150',
        ]);

    }

    public function testCorretTransactionBetweenUsersAndShopkeeper()
    {

        $user1 = factory(\App\Http\Models\User::class)->create(
            [
                "balance" => 200
            ]
        );

        $user2 = factory(\App\Http\Models\User::class)->create(
            [
                "tipouser"  => UserConstant::TIPOUSER_JURIDICO,
                "cnpj"  => "84038259000111",
                "balance" =>100
            ]
        );

        $response = $this->put('/api/transaction',
         [
           "payer" => $user1->email,
           "payee" => $user2->email,
           "value" => 50
         ]);

        $response->assertStatus(Response::HTTP_OK);

       //Testing transaction results
       $response = $this->get("/api/user/$user1->id")
       ->assertStatus(Response::HTTP_OK)
       ->assertJsonFragment(
       [
           'balance' => '150',
       ]);

       $response = $this->get("/api/user/$user2->id")
       ->assertStatus(Response::HTTP_OK)
       ->assertJsonFragment(
       [
           'balance' => '150',
       ]);


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

    public function testErrorNoBalancetoTransaction()
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

    public function testErrorTransactionValueLessThanZero()
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
           "value" => -50
         ]);

        $response->assertStatus(Response::HTTP_PRECONDITION_FAILED);

    }

    public function testErrorTransactionequalZero()
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
           "value" => 0
         ]);

        $response->assertStatus(Response::HTTP_PRECONDITION_FAILED);

    }
}

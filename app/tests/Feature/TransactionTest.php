<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TransactionTest extends TestCase
{
    use DatabaseMigrations;

    public function testCorretTransaction()
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
                "cpf"  => "11111111111",
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

        $response->assertStatus(200);

    }
}

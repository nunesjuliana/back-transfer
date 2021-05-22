<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    use DatabaseMigrations;

    public function testInsertUser()
    {
        $newUser = [
                "name" => "juliana nunes",
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "22222222222",
                "password"  => "123",
                "balance" => 30
        ];

        $result = $this->post('/api/user', $newUser);

        $result->assertStatus(Response::HTTP_CREATED);
    }

    public function testInsertShopkeeper()
    {
        $newUser = [
                "name" => "juliana nunes",
                "email" =>"cst@gmail.com",
                "tipouser"  => "1",
                "cnpj"  => "22222222222222",
                "password"  => "123",
                "balance" => 30
        ];

        $result = $this->post('/api/user', $newUser);

        $result->assertStatus(Response::HTTP_CREATED);
    }

    public function testErrorInsertSameCPF()
    {
        $user = factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"j@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "22222222222",
                "password"  => "123",
                "balance" => 30
            ]
        );

        $newUser = [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "22222222222",
                "password"  => "123",
                "balance" => 30
        ];

        $this->post('/api/user', $newUser)->assertSessionHasErrors(('cpf'));

        $this->assertEquals(1, User::count());

    }

    public function testErrorInsertSameEmail()
    {
        $user = factory(\App\Http\Models\User::class)->create(
            [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "22222222222",
                "password"  => "123",
                "balance" => 30
            ]
        );

        $newUser = [
                "email" =>"cst@gmail.com",
                "tipouser"  => "0",
                "cpf"  => "11111111111",
                "password"  => "123",
                "balance" => 30
        ];

        $this->post('/api/user', $newUser)->assertSessionHasErrors(('email'));

        $this->assertEquals(1, User::count());

    }

}
